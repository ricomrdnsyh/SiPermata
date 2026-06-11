<?php

namespace App\Http\Controllers\BAK;

use App\Models\Fakultas;
use App\Models\Template;
use App\Models\TtdSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BAKTtdSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bak.ttd.index');
    }

    public function getTtdSurat()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        $query = TtdSurat::with(['template.fakultas'])
            ->whereHas('template', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->filterColumn('nama_fakultas', function ($query, $keyword) {
                $query->whereHas('template.fakultas', function ($q) use ($keyword) {
                    $q->where('nama_fakultas', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('template', function ($query, $keyword) {
                $query->whereHas('template', function ($q) use ($keyword) {
                    $q->where('nama_template', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', 'like', "%{$keyword}%");
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->template?->fakultas?->nama_fakultas ?? '—';
            })
            ->addColumn('template', function ($row) {
                return $row->template ? $row->template->nama_template : '—';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'aktif') {
                    return '<span class="badge text-white bg-success">Aktif</span>';
                } elseif ($row->status == 'nonaktif') {
                    return '<span class="badge text-white bg-danger">Nonaktif</span>';
                } else {
                    return '<span class="badge text-white bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.ttdSurat.show', $row->id_ttd) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
            data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.ttdSurat.edit', $row->id_ttd) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
            data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'template', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return redirect()->route('bak.dashboard')->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        $template = Template::where('fakultas_id', $fakultasId)->get();

        $fakultas = Fakultas::all();

        return view('bak.ttd.create', compact('template', 'fakultas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $request->validate(
            [
                'template_id'   => 'required|exists:template,id_template',
                'nama_ttd'      => 'required',
                'nidn'          => 'required',
                'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
                'status'         => 'required',
            ],
            [
                'template_id.required'   => 'Template wajib dipilih.',
                'nama_ttd.required'      => 'Nama TTD wajib diisi.',
                'fakultas_id.required'   => 'Fakultas wajib dipilih.',
                'status.required'        => 'Status wajib diisi.',
            ]
        );

        TtdSurat::create([
            'template_id'   => $request->template_id,
            'nama_ttd'      => $request->nama_ttd,
            'nidn'          => $request->nidn,
            'fakultas_id'   => $request->fakultas_id,
            'status'        => $request->status,
        ]);

        return redirect()->route('bak.ttdSurat.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $ttd = TtdSurat::findOrFail($id);

        $template = Template::all();
        $fakultas = Fakultas::all();

        return view('bak.ttd.show', compact('ttd', 'template', 'fakultas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $ttd = TtdSurat::findOrFail($id);

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return redirect()->route('bak.dashboard')->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        $template = Template::where('fakultas_id', $fakultasId)->get();
        $fakultas = Fakultas::all();

        return view('bak.ttd.edit', compact('ttd', 'template', 'fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $request->validate(
            [
                'template_id'   => 'required|exists:template,id_template',
                'nama_ttd'      => 'required',
                'nidn'          => 'required',
                'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
                'status'        => 'required',
            ],
            [
                'template_id.required'   => 'Template wajib dipilih.',
                'nama_ttd.required'      => 'Nama TTD wajib diisi.',
                'fakultas_id.required'   => 'Fakultas wajib dipilih.',
                'status.required'        => 'Status wajib diisi.',
            ]
        );

        $ttd = TtdSurat::findOrFail($id);
        $ttd->update([
            'template_id'   => $request->template_id,
            'nama_ttd'      => $request->nama_ttd,
            'nidn'          => $request->nidn,
            'fakultas_id'   => $request->fakultas_id,
            'status'        => $request->status,
        ]);

        return redirect()->route('bak.ttdSurat.index')->with('success', 'Data berhasil diperbarui!');
    }
}

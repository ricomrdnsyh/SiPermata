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
    
    public function index()
    {
        $user = Auth::user();
        $fakultasId = $user->penduduk?->fakultas_id;
        
        $template = Template::where('fakultas_id', $fakultasId)->get();
        $fakultas = Fakultas::all();

        return view('bak.ttd.index', compact('template', 'fakultas'));
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
            ->filterColumn('nama_template', function ($query, $keyword) {
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
            ->addColumn('nama_template', function ($row) {
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
                $nama_ttd = htmlspecialchars($row->nama_ttd ?? '-');
                $nidn = htmlspecialchars($row->nidn ?? '-');
                $status = htmlspecialchars($row->status ?? '-');
                $template_id = $row->template_id ?? '';
                $fakultas_id = $row->fakultas_id ?? '';
                $nama_template = htmlspecialchars($row->template ? $row->template->nama_template : '-');
                $nama_fakultas = htmlspecialchars($row->fakultas ? $row->fakultas->nama_fakultas : '-');

                $showBtn = '<a href="javascript:void(0)" onclick="showModal(this)" data-nama="'.$nama_ttd.'" data-nidn="'.$nidn.'" data-status="'.$status.'" data-template="'.$nama_template.'" data-fakultas="'.$nama_fakultas.'" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="javascript:void(0)" onclick="editModal(this)" data-id="'.$row->id_ttd.'" data-nama="'.$nama_ttd.'" data-nidn="'.$nidn.'" data-status="'.$status.'" data-template="'.$template_id.'" data-fakultas="'.$fakultas_id.'" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'nama_template', 'status', 'action'])
            ->make(true);
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }

        $ttd = TtdSurat::findOrFail($id);
        $ttd->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    
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

    
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
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

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        TtdSurat::create([
            'template_id'   => $request->template_id,
            'nama_ttd'      => $request->nama_ttd,
            'nidn'          => $request->nidn,
            'fakultas_id'   => $request->fakultas_id,
            'status'        => $request->status,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('bak.ttdSurat.index'),
            ], 201);
        }

        return redirect()->route('bak.ttdSurat.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
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

    
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
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

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $ttd = TtdSurat::findOrFail($id);
        $ttd->update([
            'template_id'   => $request->template_id,
            'nama_ttd'      => $request->nama_ttd,
            'nidn'          => $request->nidn,
            'fakultas_id'   => $request->fakultas_id,
            'status'        => $request->status,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('bak.ttdSurat.index'),
            ], 200);
        }

        return redirect()->route('bak.ttdSurat.index')->with('success', 'Data berhasil diperbarui!');
    }
}

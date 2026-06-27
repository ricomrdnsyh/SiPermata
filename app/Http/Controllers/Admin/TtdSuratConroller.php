<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fakultas;
use App\Models\Template;
use App\Models\TtdSurat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TtdSuratConroller extends Controller
{
    
    public function index()
    {
        $fakultas = Fakultas::all();
        $template = Template::with('fakultas')->get();
        return view('admin.ttd.index', compact('fakultas', 'template'));
    }

    public function getTtdSurat(Request $request)
    {
        $data = TtdSurat::select(['id_ttd', 'template_id', 'nama_ttd', 'nidn', 'fakultas_id', 'status'])
            ->with('template', 'fakultas');

        if ($request->has('fakultas_filter') && $request->fakultas_filter != '') {
            $data->where('fakultas_id', $request->fakultas_filter);
        }

        return DataTables::of($data)
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->filterColumn('nama_template', function ($query, $keyword) {
                $query->whereHas('template', function ($q) use ($keyword) {
                    $q->where('nama_template', 'like', "%{$keyword}%");
                });
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

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_ttd . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'nama_template', 'status', 'action'])
            ->make(true);
    }

    
    public function create()
    {
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.create', compact('fakultas', 'template'));
    }

    
    public function store(Request $request)
    {
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('admin.ttdSurat.index'),
            ], 201);
        }

        return redirect()->route('admin.ttdSurat.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
    public function show(string $id)
    {
        $ttd      = TtdSurat::findOrFail($id);
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.show', compact('ttd', 'fakultas', 'template'));
    }

    
    public function edit(string $id)
    {
        $ttd      = TtdSurat::findOrFail($id);
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.edit', compact('ttd', 'fakultas', 'template'));
    }

    
    public function update(Request $request, string $id)
    {
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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('admin.ttdSurat.index'),
            ], 200);
        }

        return redirect()->route('admin.ttdSurat.index')->with('success', 'Data berhasil diperbarui!');
    }

    
    public function destroy(string $id)
    {
        $ttd = TtdSurat::findOrFail($id);
        $ttd->delete();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Data berhasil dihapus!'
        ]);
    }
}

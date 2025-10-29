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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.ttd.index');
    }

    public function getTtdSurat()
    {
        $data = TtdSurat::select(['id_ttd', 'template_id', 'nama_ttd', 'nidn', 'fakultas_id', 'status'])
            ->with('fakultas', 'fakultas');

        return DataTables::of($data)
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('template', function ($row) {
                return $row->template ? $row->template->nama_template : '—';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'aktif') {
                    return '<span class="badge bg-success">Aktif</span>';
                } elseif ($row->status == 'nonaktif') {
                    return '<span class="badge bg-danger">Nonaktif</span>';
                } else {
                    return '<span class="badge bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.ttdSurat.show', $row->id_ttd) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.ttdSurat.edit', $row->id_ttd) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_ttd . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'template', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.create', compact('fakultas', 'template'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('admin.ttdSurat.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ttd      = TtdSurat::findOrFail($id);
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.show', compact('ttd', 'fakultas', 'template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ttd      = TtdSurat::findOrFail($id);
        $fakultas = Fakultas::all();
        $template = Template::all();

        return view('admin.ttd.edit', compact('ttd', 'fakultas', 'template'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('admin.ttdSurat.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
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

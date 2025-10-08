<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Prodi::with('fakultas')->get();

        return view('admin.prodi.index', compact('data'));
    }

    public function getProdi()
    {
        $prodi = Prodi::select([
            'prodi.id_prodi',
            'prodi.fakultas_id',
            'prodi.nama_prodi',
            'prodi.singkatan',
            'prodi.gelar',
            'prodi.status'
        ])
            ->with('fakultas');

        return DataTables::of($prodi)
            ->editColumn('status', function ($row) {
                if ($row->status == 'aktif') {
                    return '<span class="badge bg-success">Aktif</span>';
                } elseif ($row->status == 'nonaktif') {
                    return '<span class="badge bg-danger">Nonaktif</span>';
                } else {
                    return '<span class="badge bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.prodi.show', $row->id_prodi) . '" class="btn btn-sm btn-light btn-active-light-info text-center">
                <i class="fas fa-eye"></i></a>';

                $editBtn = '<a href="' . route('admin.prodi.edit', $row->id_prodi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center">
                <i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id_prodi . '" class="btn btn-sm btn-light btn-active-light-danger text-center delete-btn">
                <i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['status', 'nama_fakultas', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Fakultas::all();

        return view('admin.prodi.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'fakultas_id' => 'required|exists:fakultas,id_fakultas',
                'nama_prodi'  => 'required',
                'singkatan'   => 'required',
                'gelar'       => 'required',
                'status'      => 'required',
            ],
            [
                'fakultas_id.required' => 'Fakultas harus diisi.',
                'nama_prodi.required'  => 'Nama Prodi harus diisi.',
                'singkatan.required'   => 'Singkatan harus diisi.',
                'gelar.required'       => 'Gelar harus diisi.',
                'status.required'      => 'Status harus diisi.',
            ]
        );

        Prodi::create([
            'fakultas_id' => $request->fakultas_id,
            'nama_prodi'  => $request->nama_prodi,
            'singkatan'   => $request->singkatan,
            'gelar'       => $request->gelar,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prodi = Prodi::with('fakultas')->findOrFail($id);

        return view('admin.prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prodi = Prodi::findOrFail($id);
        $fakultas = Fakultas::all();

        return view('admin.prodi.edit', compact('prodi', 'fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'fakultas_id' => 'required|exists:fakultas,id_fakultas',
                'nama_prodi'  => 'required',
                'singkatan'   => 'required',
                'gelar'       => 'required',
                'status'      => 'required',
            ],
            [
                'fakultas_id.required' => 'Fakultas harus diisi.',
                'nama_prodi.required'  => 'Nama Prodi harus diisi.',
                'singkatan.required'   => 'Singkatan harus diisi.',
                'gelar.required'       => 'Gelar harus diisi.',
                'status.required'      => 'Status harus diisi.',
            ]
        );

        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'fakultas_id' => $request->fakultas_id,
            'nama_prodi'  => $request->nama_prodi,
            'singkatan'   => $request->singkatan,
            'gelar'       => $request->gelar,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.prodi.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Prodi::findOrFail($id);
        $data->delete();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Data berhasil dihapus!'
        ]);
    }
}

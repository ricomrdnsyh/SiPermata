<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Penduduk::all();

        return view('admin.penduduk.index', compact('data'));
    }

    public function getMahasiswa()
    {
        $data = Penduduk::select(['id_penduduk', 'fakultas_id', 'prodi_id', 'nama_penduduk', 'nidn', 'email', 'no_hp'])
            ->with('fakultas', 'prodi');

        return DataTables::of($data)
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('nama_prodi', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.penduduk.show', $row->id_penduduk) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.penduduk.edit', $row->id_penduduk) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id_penduduk . '" class="btn btn-sm btn-light btn-active-light-danger text-center delete-btn" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'nama_prodi', 'action'])
            ->make(true);
    }

    public function getProdi($fakultas_id)
    {
        $prodi = Prodi::where('fakultas_id', $fakultas_id)->get();

        return response()->json($prodi);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.penduduk.create', compact('fakultas', 'prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
            'prodi_id'      => 'required|exists:prodi,id_prodi',
            'nidn'          => 'required|unique:penduduk,nidn',
            'nama_penduduk' => 'required',
            'email'         => 'required|email|unique:penduduk,email',
            'no_hp'         => 'nullable',
        ], [
            'fakultas_id.required'   => 'Fakultas harus diisi.',
            'prodi_id.required'      => 'Program Studi harus diisi.',
            'nidn.required'          => 'NIDN harus diisi.',
            'nidn.unique'            => 'NIDN sudah terdaftar.',
            'nama_penduduk.required' => 'Nama Penduduk harus diisi.',
            'email.required'         => 'Email harus diisi.',
            'email.unique'           => 'Email sudah terdaftar.',
        ]);

        Penduduk::create([
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
            'nidn'          => $request->nidn,
            'nama_penduduk' => $request->nama_penduduk,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data     = Penduduk::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.penduduk.show', compact('data', 'fakultas', 'prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data     = Penduduk::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.penduduk.edit', compact('data', 'fakultas', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'nidn'          => 'required',
                'nama_penduduk' => 'required',
                'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
                'prodi_id'      => 'required|exists:prodi,id_prodi',
                'email'         => 'required',
                'no_hp'         => 'nullable',
            ],
            [
                'nidn.required'          => 'NIM wajib diisi.',
                'nama_penduduk.required' => 'Nama wajib diisi.',
                'fakultas_id.required'   => 'Fakultas wajib dipilih.',
                'prodi_id.required'      => 'Prodi wajib dipilih.',
                'email.required'         => 'Email wajib diisi.',
                'no_hp.required'         => 'No Telepon wajib diisi.',
            ]
        );

        $data = Penduduk::findOrFail($id);

        $data->update([
            'nidn'          => $request->nidn,
            'nama_penduduk' => $request->nama_penduduk,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('admin.penduduk.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Penduduk::findOrFail($id);
        $data->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Mahasiswa::all();

        return view('admin.mahasiswa.index', compact('data'));
    }

    public function getMahasiswa()
    {
        $data = Mahasiswa::select(['nim', 'prodi_id', 'fakultas_id', 'nama', 'jenis_kelamin', 'email', 'no_hp'])
            ->with('fakultas', 'prodi');

        return DataTables::of($data)
            ->editColumn('jenis_kelamin', function ($row) {
                if ($row->jenis_kelamin == 'L') {
                    return '<span>Laki-laki</span>';
                } elseif ($row->jenis_kelamin == 'P') {
                    return '<span>Perempuan</span>';
                }
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('nama_prodi', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.mahasiswa.show', $row->nim) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.mahasiswa.edit', $row->nim) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->nim . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['jenis_kelamin', 'nama_fakultas', 'nama_prodi', 'action'])
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

        return view('admin.mahasiswa.create', compact('fakultas', 'prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nim'           => 'required|unique:mahasiswa,nim',
                'nama'          => 'required',
                'jenis_kelamin' => 'required',
                'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
                'prodi_id'      => 'required|exists:prodi,id_prodi',
                'email'         => 'required|email|unique:mahasiswa,email',
                'no_hp'         => 'nullable',
            ],
            [
                'nim.required'           => 'NIM wajib diisi.',
                'nim.unique'             => 'NIM sudah terdaftar.',
                'nama.required'          => 'Nama wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'fakultas_id.required'   => 'Fakultas wajib dipilih.',
                'prodi_id.required'      => 'Prodi wajib dipilih.',
                'email.required'         => 'Email wajib diisi.',
                'email.unique'           => 'Email sudah terdaftar.',
            ]
        );

        Mahasiswa::create([
            'nim'           => $request->nim,
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data     = Mahasiswa::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.mahasiswa.show', compact('data', 'fakultas', 'prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data     = Mahasiswa::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.mahasiswa.edit', compact('data', 'fakultas', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'nim'           => 'required',
                'nama'          => 'required',
                'jenis_kelamin' => 'required',
                'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
                'prodi_id'      => 'required|exists:prodi,id_prodi',
                'email'         => 'required',
                'no_hp'         => 'nullable',
            ],
            [
                'nim.required'           => 'NIM wajib diisi.',
                'nama.required'          => 'Nama wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'fakultas_id.required'   => 'Fakultas wajib dipilih.',
                'prodi_id.required'      => 'Prodi wajib dipilih.',
                'email.required'         => 'Email wajib diisi.',
            ]
        );

        $data = Mahasiswa::findOrFail($id);
        $data->update([
            'nim'           => $request->nim,
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
            'email'         => $request->email,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Mahasiswa::findOrFail($id);
        $data->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
    }
}

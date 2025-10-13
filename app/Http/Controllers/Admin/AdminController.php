<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Penduduk;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::with('mahasiswa', 'penduduk.jabatan')->get();

        return view('admin.users.index', compact('data'));
    }

    public function getAdmin()
    {
        $data = User::select(['id', 'identifier', 'nama', 'type', 'password']);

        return DataTables::of($data)
            ->editColumn('type', function ($row) {
                if ($row->type == 'mahasiswa') {
                    return '<span class="badge bg-warning">Mahasiswa</span>';
                } elseif ($row->type == 'penduduk') {
                    return '<span class="badge bg-success">Penduduk</span>';
                } elseif ($row->type == 'admin') {
                    return '<span class="badge bg-primary">Admin</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $deleteBtn . '</div>';
            })
            ->rawColumns(['type', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        $penduduk  = Penduduk::with('jabatan')->get(); // untuk tahu BAK/Dekan

        return view('admin.users.create', compact('mahasiswa', 'penduduk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'         => 'required|in:mahasiswa,penduduk,admin',
            'reference_id' => 'required_if:type,mahasiswa,penduduk',
            'identifier'   => 'required|unique:users,identifier',
            'nama'         => 'required|string|max:255',
        ]);

        // Password default
        $password = '123456';

        if ($request->type === 'admin') {
            // Buat admin manual
            User::create([
                'identifier'   => $request->identifier,
                'nama'         => $request->nama,
                'type'         => 'admin',
                'reference_id' => 'admin', // atau null jika kolom nullable
                'password'     => Hash::make($password),
            ]);
        } elseif ($request->type === 'mahasiswa') {
            $mahasiswa = Mahasiswa::where('nim', $request->reference_id)->firstOrFail();
            User::create([
                'identifier'   => $mahasiswa->nim,
                'nama'         => $mahasiswa->nama,
                'type'         => 'mahasiswa',
                'reference_id' => $mahasiswa->nim,
                'password'     => Hash::make($password),
            ]);
        } else {
            // type = penduduk
            $penduduk = Penduduk::where('id_penduduk', $request->reference_id)->firstOrFail();
            $identifier = $penduduk->nidn ?: $penduduk->email;
            if (!$identifier) {
                return back()->withErrors(['identifier' => 'Penduduk ini tidak memiliki NIDN atau email.']);
            }

            User::create([
                'identifier'   => $identifier,
                'nama'         => $penduduk->nama_penduduk,
                'type'         => 'penduduk',
                'reference_id' => $penduduk->id_penduduk,
                'password'     => Hash::make($password),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

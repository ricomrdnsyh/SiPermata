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
use Illuminate\Support\Facades\Validator;

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
                    return '<span class="badge text-white bg-warning">Mahasiswa</span>';
                } elseif ($row->type == 'penduduk') {
                    return '<span class="badge text-white bg-success">Penduduk</span>';
                } elseif ($row->type == 'admin') {
                    return '<span class="badge text-white bg-primary">Admin</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $deleteBtn . '</div>';
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
        $rules = [
            'type' => 'required|in:mahasiswa,penduduk,admin',
        ];

        if ($request->type === 'admin') {
            $rules['identifier'] = 'required|unique:users,identifier';
            $rules['nama']       = 'required|string|max:255';
            $rules['password']   = 'required|string|min:6|max:255';
        } elseif ($request->type === 'mahasiswa') {
            $rules['m_reference_id'] = 'required';
            $rules['m_password']     = 'required|string|min:6|max:255';
        } elseif ($request->type === 'penduduk') {
            $rules['p_reference_id'] = 'required';
            $rules['p_password']     = 'required|string|min:6|max:255';
        }

        $validated = $request->validate($rules);

        try {
            if ($request->type === 'admin') {
                User::create([
                    'identifier'   => $request->identifier,
                    'nama'         => $request->nama,
                    'type'         => 'admin',
                    'reference_id' => 'admin',
                    'password'     => Hash::make($request->password),
                ]);
            } elseif ($request->type === 'mahasiswa') {

                $mahasiswa = Mahasiswa::where('nim', $request->m_reference_id)->first();

                if (! $mahasiswa) {
                    return back()->withInput()->withErrors(['m_reference_id' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.']);
                }

                if (User::where('identifier', $mahasiswa->nim)->exists()) {
                    return back()->withInput()->withErrors(['m_reference_id' => 'User untuk NIM ini sudah ada.']);
                }

                User::create([
                    'identifier'   => $mahasiswa->nim,
                    'nama'         => $mahasiswa->nama,
                    'type'         => 'mahasiswa',
                    'reference_id' => $mahasiswa->nim,
                    'password'     => Hash::make($request->m_password),
                ]);
            } else {
                $penduduk = Penduduk::where('id_penduduk', $request->p_reference_id)->first();

                if (! $penduduk) {
                    return back()->withInput()->withErrors(['p_reference_id' => 'Penduduk dengan ID tersebut tidak ditemukan.']);
                }

                $identifier = $penduduk->id_penduduk;

                if (! $identifier) {
                    return back()->withInput()->withErrors(['p_reference_id' => 'Penduduk ini tidak memiliki NIDN atau email.']);
                }

                if (User::where('identifier', $identifier)->exists()) {
                    return back()->withInput()->withErrors(['p_reference_id' => 'User untuk penduduk ini sudah ada (identifier sudah dipakai).']);
                }

                User::create([
                    'identifier'   => $identifier,
                    'nama'         => $penduduk->nama_penduduk,
                    'type'         => 'penduduk',
                    'reference_id' => $penduduk->id_penduduk,
                    'password'     => Hash::make($request->p_password),
                ]);
            }

            return redirect()->route('admin.users.index')->with('success', 'Data berhasil ditambahkan!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('failed', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

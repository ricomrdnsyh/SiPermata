<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jabatan;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use App\Models\Fakultas;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Jabatan::with(['penduduk', 'fakultas'])->get();

        return view('admin.jabatan.index', compact('data'));
    }

    public function getJabatan()
    {
        $data = Jabatan::select(['id_jabatan', 'penduduk_id', 'status', 'urutan', 'fakultas_id'])
            ->with('penduduk', 'fakultas');

        return DataTables::of($data)
            ->editColumn('status', function ($row) {
                if ($row->status == 'BAK') {
                    return '<span class="badge bg-warning">BAK</span>';
                } elseif ($row->status == 'DEKAN') {
                    return '<span class="badge bg-primary">Dekan</span>';
                } else {
                    return '<span class="badge bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('nama_penduduk', function ($row) {
                return $row->penduduk ? $row->penduduk->nama_penduduk : '—';
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.jabatan.show', $row->id_jabatan) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.jabatan.edit', $row->id_jabatan) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_jabatan . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['status', 'nama_fakultas', 'nama_penduduk', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penduduk = Penduduk::all();

        return view('admin.jabatan.create', compact('penduduk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id_penduduk',
            'status'      => 'required',
            'urutan'      => 'required',
        ], [
            'penduduk_id.required' => 'Penduduk harus diisi.',
            'status.required'      => 'Status harus diisi.',
            'urutan.required'      => 'Urutan harus diisi.',
        ]);

        // Ambil data penduduk untuk dapatkan fakultas_id
        $penduduk = Penduduk::where('id_penduduk', $request->penduduk_id)->first();
        if (!$penduduk || !$penduduk->fakultas_id) {
            throw ValidationException::withMessages([
                'penduduk_id' => 'Penduduk tidak ditemukan atau tidak memiliki fakultas.',
            ]);
        }

        Jabatan::create([
            'penduduk_id' => $request->penduduk_id,
            'status'      => $request->status,
            'urutan'      => $request->urutan,
            'fakultas_id' => $penduduk->fakultas_id,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Jabatan::with(['penduduk', 'fakultas'])->findOrFail($id);

        return view('admin.jabatan.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Jabatan::findOrFail($id);
        $penduduk = Penduduk::all();

        return view('admin.jabatan.edit', compact('data', 'penduduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id_penduduk',
            'status'      => 'required',
            'urutan'      => 'required',
        ], [
            'penduduk_id.required' => 'Penduduk harus diisi.',
            'status.required'      => 'Status harus diisi.',
            'urutan.required'      => 'Urutan harus diisi.',
        ]);

        // Ambil data penduduk untuk dapatkan fakultas_id
        $penduduk = Penduduk::where('id_penduduk', $request->penduduk_id)->first();
        if (!$penduduk || !$penduduk->fakultas_id) {
            throw ValidationException::withMessages([
                'penduduk_id' => 'Penduduk tidak ditemukan atau tidak memiliki fakultas.',
            ]);
        }

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'penduduk_id' => $request->penduduk_id,
            'status'      => $request->status,
            'urutan'      => $request->urutan,
            'fakultas_id' => $penduduk->fakultas_id,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
    }
}

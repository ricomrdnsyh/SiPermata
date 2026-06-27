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
    
    public function index()
    {
        $data = Jabatan::with(['penduduk', 'fakultas'])->get();
        $penduduk = Penduduk::with('fakultas')->get();
        $listFakultas = Fakultas::all();

        return view('admin.jabatan.index', compact('data', 'penduduk', 'listFakultas'));
    }

    public function getJabatan(Request $request)
    {
        $data = Jabatan::select(['id_jabatan', 'penduduk_id', 'status', 'fakultas_id'])
            ->with('penduduk', 'fakultas')
            ->orderBy('id_jabatan', 'desc');

        if ($request->has('fakultas_filter') && $request->fakultas_filter != '') {
            $data->where('fakultas_id', $request->fakultas_filter);
        }

        return DataTables::of($data)
            ->filterColumn('nama_penduduk', function ($query, $keyword) {
                $query->whereHas('penduduk', function ($q) use ($keyword) {
                    $q->where('nama_penduduk', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'BAK') {
                    return '<span class="badge text-white bg-warning">BAK</span>';
                } elseif ($row->status == 'DEKAN') {
                    return '<span class="badge text-white bg-primary">Dekan</span>';
                } else {
                    return '<span class="badge text-white bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('nama_penduduk', function ($row) {
                return $row->penduduk ? $row->penduduk->nama_penduduk : '—';
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('action', function ($row) {
                $nama_penduduk = $row->penduduk ? htmlspecialchars($row->penduduk->nama_penduduk) : '—';
                $nama_fakultas = $row->fakultas ? htmlspecialchars($row->fakultas->nama_fakultas) : '—';
                $status = htmlspecialchars($row->status);
                
                $showBtn = '<a href="javascript:void(0)" onclick="showModal(this)" data-nama="'.$nama_penduduk.'" data-fakultas="'.$nama_fakultas.'" data-status="'.$status.'" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="javascript:void(0)" onclick="editModal(this)" data-id="'.$row->id_jabatan.'" data-penduduk_id="'.$row->penduduk_id.'" data-status="'.$status.'" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_jabatan . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['status', 'nama_fakultas', 'nama_penduduk', 'action'])
            ->make(true);
    }

    
    public function create()
    {
        $penduduk = Penduduk::with('fakultas')->get();

        return view('admin.jabatan.create', compact('penduduk'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id_penduduk',
            'status'      => 'required|string',
        ], [
            'penduduk_id.required' => 'Penduduk harus diisi.',
            'status.required'      => 'Status harus diisi.',
        ]);

        $penduduk = Penduduk::where('id_penduduk', $request->penduduk_id)->first();
        if (!$penduduk || !$penduduk->fakultas_id) {
            throw ValidationException::withMessages([
                'penduduk_id' => 'Penduduk tidak ditemukan atau tidak memiliki fakultas.',
            ]);
        }
        Jabatan::create([
            'penduduk_id' => $request->penduduk_id,
            'status'      => $request->status,
            'fakultas_id' => $penduduk->fakultas_id,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
    public function show(string $id)
    {
        $data = Jabatan::with(['penduduk', 'fakultas'])->findOrFail($id);

        return view('admin.jabatan.show', compact('data'));
    }

    
    public function edit(string $id)
    {
        $data = Jabatan::findOrFail($id);
        $penduduk = Penduduk::all();

        return view('admin.jabatan.edit', compact('data', 'penduduk'));
    }

    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduk,id_penduduk',
            'status'      => 'required',
        ], [
            'penduduk_id.required' => 'Penduduk harus diisi.',
            'status.required'      => 'Status harus diisi.',
        ]);

        $jabatan = Jabatan::findOrFail($id);

        
        $penduduk = Penduduk::where('id_penduduk', $request->penduduk_id)->first();
        if (!$penduduk || !$penduduk->fakultas_id) {
            throw ValidationException::withMessages([
                'penduduk_id' => 'Penduduk tidak ditemukan atau tidak memiliki fakultas.',
            ]);
        }

        $jabatan->update([
            'penduduk_id' => $request->penduduk_id,
            'status'      => $request->status,
            'fakultas_id' => $penduduk->fakultas_id,
        ]);

        return redirect()->route('admin.jabatan.index')->with('success', 'Data berhasil diupdate!');
    }

    
    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Fakultas::all();

        return view('admin.fakultas.index', compact('data'));
    }

    public function getFakultas()
    {
        $data = Fakultas::select(['id_fakultas', 'nama_fakultas', 'singkatan', 'status']);

        return DataTables::of($data)
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
                $showBtn = '<a href="' . route('admin.fakultas.show', $row->id_fakultas) . '" class="btn btn-sm btn-light btn-active-light-info text-center">
                <i class="fas fa-eye"></i></a>';

                $editBtn = '<a href="' . route('admin.fakultas.edit', $row->id_fakultas) . '" class="btn btn-sm btn-light btn-active-light-warning text-center">
                <i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_fakultas . ')" class="btn btn-sm btn-light btn-active-light-danger text-center">
                <i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('admin.fakultas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required',
            'singkatan'     => 'required',
            'status'        => 'required|in:aktif,nonaktif',
        ], [
            'nama_fakultas.required' => 'Nama Fakultas wajib diisi.',
            'singkatan.required'     => 'Singkatan wajib diisi.',
            'status.required'        => 'Status wajib diisi.',
        ]);

        Fakultas::create([
            'nama_fakultas' => $request->nama_fakultas,
            'singkatan'     => $request->singkatan,
            'status'        => $request->status,
        ]);

        return redirect()->route('admin.fakultas.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Fakultas::findOrFail($id);

        return view('admin.fakultas.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Fakultas::findOrFail($id);

        return view('admin.fakultas.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_fakultas' => 'required',
            'singkatan'     => 'required',
            'status'        => 'required|in:aktif,nonaktif',
        ], [
            'nama_fakultas.required' => 'Nama Fakultas wajib diisi.',
            'singkatan.required'     => 'Singkatan wajib diisi.',
            'status.required'        => 'Status wajib diisi.',
        ]);

        $data = Fakultas::findOrFail($id);
        $data->update([
            'nama_fakultas' => $request->nama_fakultas,
            'singkatan'     => $request->singkatan,
            'status'        => $request->status,
        ]);

        return redirect()->route('admin.fakultas.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Fakultas::findOrFail($id);
        $data->delete();

        return response()->json([
            'status'    => 'success',
            'message'   => 'Data berhasil dihapus.'
        ]);
    }
}

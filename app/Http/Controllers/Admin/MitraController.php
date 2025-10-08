<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Mitra::all();

        return view('admin.mitra.index', compact('data'));
    }

    public function getMitra()
    {
        $data = Mitra::select(['id_mitra', 'nama_mitra']);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.mitra.show', $row->id_mitra) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.mitra.edit', $row->id_mitra) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_mitra . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_mitra' => 'required',
            ],
            [
                'nama_mitra.required' => 'Nama Mitra wajib diisi.',
            ]
        );

        Mitra::create([
            'nama_mitra' => $request->nama_mitra,
        ]);

        return redirect()->route('admin.mitra.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mitra = Mitra::findOrFail($id);

        return view('admin.mitra.show', compact('mitra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mitra = Mitra::findOrFail($id);

        return view('admin.mitra.edit', compact('mitra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'nama_mitra' => 'required',
            ],
            [
                'nama_mitra.required' => 'Nama Mitra wajib diisi.',
            ]
        );

        $mitra = Mitra::findOrFail($id);
        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
        ]);

        return redirect()->route('admin.mitra.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

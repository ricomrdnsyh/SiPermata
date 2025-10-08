<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = TahunAkademik::all();

        return view('admin.akademik.index', compact('data'));
    }

    public function getAkademik()
    {
        $data = TahunAkademik::select(['id_akademik', 'kode_akademik', 'tahun_akademik']);

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.akademik.show', $row->id_akademik) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.akademik.edit', $row->id_akademik) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_akademik . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
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
        return view('admin.akademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'kode_akademik'  => 'required',
                'tahun_akademik' => 'required',
            ],
            [
                'kode_akademik.required'  => 'Kode Akademik wajib diisi.',
                'tahun_akademik.required' => 'Tahun Akademik wajib diisi.',
            ]
        );

        TahunAkademik::create([
            'kode_akademik'  => $request->kode_akademik,
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        return redirect()->route('admin.akademik.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $akademik = TahunAkademik::findOrFail($id);

        return view('admin.akademik.show', compact('akademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akademik = TahunAkademik::findOrFail($id);

        return view('admin.akademik.edit', compact('akademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode_akademik'  => 'required',
            'tahun_akademik' => 'required',
        ]);

        $akademik = TahunAkademik::findOrFail($id);
        $akademik->update([
            'kode_akademik'  => $request->kode_akademik,
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        return redirect()->route('admin.akademik.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $akademik = TahunAkademik::findOrFail($id);
        $akademik->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

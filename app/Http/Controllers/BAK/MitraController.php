<?php

namespace App\Http\Controllers\BAK;

use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    
    public function index()
    {
        $data = Mitra::all();

        return view('bak.mitra.index', compact('data'));
    }

    public function getMitra()
    {
        $data = Mitra::select(['id_mitra', 'nama_mitra']);

        return DataTables::of($data)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('action', function ($row) {
                $nama_mitra = htmlspecialchars($row->nama_mitra ?? '-');
                $showBtn = '<a href="javascript:void(0)" onclick="showModal(this)" data-nama="'.$nama_mitra.'" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="javascript:void(0)" onclick="editModal(this)" data-id="'.$row->id_mitra.'" data-nama="'.$nama_mitra.'" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy(string $id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    
    public function create()
    {
        return view('bak.mitra.create');
    }

    

    public function store(Request $request)
    {
        $data = [
            'nama_mitra' => preg_replace('/\s+/', ' ', trim((string) $request->nama_mitra)),
        ];

        $validator = Validator::make(
            $data,
            ['nama_mitra' => 'required|unique:mitra,nama_mitra'],
            [
                'nama_mitra.required' => 'Nama Mitra wajib diisi.',
                'nama_mitra.unique'   => 'Mitra tersebut sudah ada.',
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            Mitra::create($data);
        } catch (QueryException $e) {
            $sqlState   = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;

            if ($sqlState === '23000' || $sqlState === '23505' || $driverCode === 1062) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors'  => ['nama_mitra' => ['Mitra tersebut sudah ada.']],
                    ], 422);
                }
                return back()->withErrors(['nama_mitra' => 'Mitra tersebut sudah ada.'])->withInput();
            }

            throw $e;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('bak.mitra.index'),
            ], 201);
        }

        return redirect()->route('bak.mitra.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
    public function show(string $id)
    {
        $mitra = Mitra::findOrFail($id);

        return view('bak.mitra.show', compact('mitra'));
    }

    
    public function edit(string $id)
    {
        $mitra = Mitra::findOrFail($id);

        return view('bak.mitra.edit', compact('mitra'));
    }

    public function update(Request $request, string $id)
    {
        $data = [
            'nama_mitra' => preg_replace('/\s+/', ' ', trim((string) $request->nama_mitra)),
        ];

        $validator = Validator::make(
            $data,
            ['nama_mitra' => 'required|unique:mitra,nama_mitra,' . $id . ',id_mitra'],
            [
                'nama_mitra.required' => 'Nama Mitra wajib diisi.',
                'nama_mitra.unique'   => 'Mitra tersebut sudah ada.',
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mitra = Mitra::findOrFail($id);
            $mitra->update($data);
        } catch (QueryException $e) {
            $sqlState   = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;

            if ($sqlState === '23000' || $sqlState === '23505' || $driverCode === 1062) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors'  => ['nama_mitra' => ['Mitra tersebut sudah ada.']],
                    ], 422);
                }
                return back()->withErrors(['nama_mitra' => 'Mitra tersebut sudah ada.'])->withInput();
            }

            throw $e;
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('bak.mitra.index'),
            ], 200);
        }

        return redirect()->route('bak.mitra.index')->with('success', 'Data berhasil diupdate!');
    }
}

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
    /**
     * Display a listing of the resource.
     */
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
                $showBtn = '<a href="' . route('bak.mitra.show', $row->id_mitra) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.mitra.edit', $row->id_mitra) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bak.mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     */

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mitra = Mitra::findOrFail($id);

        return view('bak.mitra.show', compact('mitra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
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

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
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

    public function getMahasiswa(Request $request)
    {
        $url      = env('EXT_API_URL');
        $token    = env('EXT_API_TOKEN');
        $username = env('EXT_API_USERNAME');
        $password = env('EXT_API_PASSWORD');

        $draw   = (int) $request->input('draw');
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        if ($length <= 0) {
            $length = 10;
        }

        $page = intval($start / $length) + 1;

        $response = Http::withHeaders([
            'X-Token'    => $token,
            'X-Username' => $username,
            'X-Password' => $password,
        ])->post($url . '?page=' . $page, [
            'kategori' => 'mahasiswa',
            'page' => $length,
        ]);

        if ($response->failed()) {
            return response()->json([
                'draw'            => $draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
            ]);
        }

        $json    = $response->json();
        $wrapper = $json['data'] ?? [];
        $rows    = $wrapper['data'] ?? [];
        $total   = (int) ($wrapper['total'] ?? count($rows));

        // opsional: mapping L/P → span
        foreach ($rows as &$row) {
            if (($row['jenis_kelamin'] ?? null) === 'L') {
                $row['jenis_kelamin'] = '<span>Laki-laki</span>';
            } elseif (($row['jenis_kelamin'] ?? null) === 'P') {
                $row['jenis_kelamin'] = '<span>Perempuan</span>';
            }
        }
        unset($row);

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows,
        ]);
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

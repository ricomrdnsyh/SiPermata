<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Services\SSOClient;
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

    public function getMahasiswa()
    {
        $data = Mahasiswa::select(['nim', 'fakultas_id', 'prodi_id', 'nama', 'jenis_kelamin', 'email', 'no_hp'])
            ->with('fakultas', 'prodi');

        return DataTables::of($data)
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('nama_prodi', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '—';
            })
            ->editColumn('jenis_kelamin', function ($row) {
                if ($row->jenis_kelamin == 'L') {
                    return '<span>Laki-laki</span>';
                } else {
                    return '<span>Perempuan</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.mahasiswa.show', $row->nim) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'nama_prodi', 'jenis_kelamin', 'action'])
            ->make(true);
    }

    public function syncFromApi(SSOClient $client)
    {
        try {
            $items = $client->getMahasiswa();

            $created   = 0;
            $updated   = 0;
            $unchanged = 0;
            $totalApi  = count($items);

            foreach ($items as $mhs) {
                $data = [
                    'prodi_id'      => $mhs['id_sms']        ?? null,
                    'fakultas_id'   => $mhs['id_fakultas']   ?? null,
                    'nama'          => $mhs['nama']          ?? null,
                    'jenis_kelamin' => $mhs['jenis_kelamin'] ?? null,
                    'email'         => $mhs['email']         ?? null,
                    'no_hp'         => $mhs['no_hp']         ?? null,
                ];

                $mahasiswa = Mahasiswa::where('nim', $mhs['nim'])->first();

                if (! $mahasiswa) {
                    Mahasiswa::create(array_merge([
                        'nim' => $mhs['nim'],
                    ], $data));

                    $created++;
                } else {
                    $mahasiswa->fill($data);

                    if ($mahasiswa->isDirty()) {
                        $mahasiswa->save();
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                }
            }

            $message = "Sinkron data mahasiswa selesai. "
                . "Baru: {$created}, diupdate: {$updated}, tidak berubah: {$unchanged}. "
                . "Total data dari API: {$totalApi} mahasiswa.";

            return redirect()->route('admin.mahasiswa.index')->with('success', $message);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.mahasiswa.index')->with('failed', 'Sinkron data mahasiswa gagal: ' . $e->getMessage());
        }
    }


    public function show(string $id)
    {
        $data     = Mahasiswa::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.mahasiswa.show', compact('data', 'fakultas', 'prodi'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Services\SSOClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Prodi::with('fakultas')->get();

        return view('admin.prodi.index', compact('data'));
    }

    public function getProdi()
    {
        $prodi = Prodi::select([
            'prodi.id_prodi',
            'prodi.fakultas_id',
            'prodi.nama_prodi',
            'prodi.singkatan',
            'prodi.status'
        ])
            ->with('fakultas');

        return DataTables::of($prodi)
            ->editColumn('status', function ($row) {
                if ($row->status == 'aktif') {
                    return '<span class="badge bg-success">Aktif</span>';
                } elseif ($row->status == 'nonaktif') {
                    return '<span class="badge bg-danger">Nonaktif</span>';
                } else {
                    return '<span class="badge bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.prodi.show', $row->id_prodi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['status', 'nama_fakultas', 'action'])
            ->make(true);
    }

    public function syncFromApi(SSOClient $client)
    {
        try {
            $created   = 0;
            $updated   = 0;
            $unchanged = 0;
            $totalApi  = 0;

            $fakultasList = Fakultas::orderBy('id_fakultas')->get();

            foreach ($fakultasList as $fak) {
                $items = $client->getProdiByFakultas($fak->id_fakultas);
                $totalApi += count($items);

                foreach ($items as $item) {
                    $status = $item['status'] ?? 'active';

                    if ($status === 'active') {
                        $status = 'aktif';
                    } elseif ($status === 'inactive') {
                        $status = 'nonaktif';
                    }

                    $data = [
                        'fakultas_id' => $item['id_fakultas'],
                        'nama_prodi'  => $item['prodi'],
                        'singkatan'   => $item['singkatan'] ?? null,
                        'status'      => $status,
                    ];

                    $prodi = Prodi::where('id_prodi', $item['id_sms'])->first();

                    if (! $prodi) {
                        Prodi::create(array_merge([
                            'id_prodi' => $item['id_sms'],
                        ], $data));

                        $created++;
                    } else {
                        $prodi->fill($data);

                        if ($prodi->isDirty()) {
                            $prodi->save();
                            $updated++;
                        } else {
                            $unchanged++;
                        }
                    }
                }
            }

            $message = "Sinkron data program studi selesai. "
                . "Baru: {$created}, diupdate: {$updated}, tidak berubah: {$unchanged}. "
                . "Total data dari API: {$totalApi} prodi.";

            return redirect()->route('admin.prodi.index')->with('success', $message);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.prodi.index')->with('failed', 'Sinkron data program studi gagal: ' . $e->getMessage());
        }
    }


    public function show(string $id)
    {
        $prodi = Prodi::with('fakultas')->findOrFail($id);

        return view('admin.prodi.show', compact('prodi'));
    }
}

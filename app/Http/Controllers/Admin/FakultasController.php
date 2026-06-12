<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fakultas;
use App\Services\SSOClient;
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
                    return '<span class="badge text-white bg-success">Aktif</span>';
                } elseif ($row->status == 'nonaktif') {
                    return '<span class="badge text-white bg-danger">Nonaktif</span>';
                } else {
                    return '<span class="badge text-white bg-secondary">' . $row->status . '</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $nama_fakultas = htmlspecialchars($row->nama_fakultas ?? '-');
                $singkatan = htmlspecialchars($row->singkatan ?? '-');
                $status = htmlspecialchars($row->status ?? '-');

                $showBtn = '<a href="javascript:void(0)" onclick="showModal(this)" data-nama="'.$nama_fakultas.'" data-singkatan="'.$singkatan.'" data-status="'.$status.'" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . '</div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function syncFromApi(SSOClient $client)
    {
        try {
            $items = $client->getFakultasFromApi();

            $created   = 0;
            $updated   = 0;
            $unchanged = 0;

            foreach ($items as $item) {
                $status = $item['status'] ?? 'active';

                if ($status === 'active') {
                    $status = 'aktif';
                } elseif ($status === 'inactive') {
                    $status = 'nonaktif';
                }

                $data = [
                    'nama_fakultas' => $item['fakultas'],
                    'singkatan'     => $item['singkatan'] ?? null,
                    'status'        => $status,
                ];

                $fakultas = Fakultas::where('id_fakultas', $item['id_fakultas'])->first();

                if (! $fakultas) {
                    Fakultas::create(array_merge([
                        'id_fakultas' => $item['id_fakultas'],
                    ], $data));

                    $created++;
                } else {
                    $fakultas->fill($data);

                    if ($fakultas->isDirty()) {
                        $fakultas->save();
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                }
            }

            $totalApi = count($items);

            $message = "Sinkron data fakultas selesai. "
                . "Baru: {$created}, diupdate: {$updated}, tidak berubah: {$unchanged}. "
                . "Total data dari API: {$totalApi}.";

            return redirect()->route('admin.fakultas.index')->with('success', $message);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('admin.fakultas.index')->with('failed', 'Sinkron data fakultas gagal: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $data = Fakultas::findOrFail($id);

        return view('admin.fakultas.show', compact('data'));
    }
}

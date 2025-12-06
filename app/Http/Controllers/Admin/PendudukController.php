<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Penduduk;
use App\Services\SSOClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Penduduk::all();

        return view('admin.penduduk.index', compact('data'));
    }

    public function getPenduduk()
    {
        $data = Penduduk::select(['id_penduduk', 'fakultas_id', 'prodi_id', 'nama_penduduk', 'nidn', 'email', 'no_hp'])
            ->with('fakultas', 'prodi');

        return DataTables::of($data)
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('nama_prodi', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.penduduk.show', $row->id_penduduk) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['nama_fakultas', 'nama_prodi', 'action'])
            ->make(true);
    }

    private function mapLembagaToFakultas(?int $idLembaga): ?int
    {
        if (is_null($idLembaga)) {
            return null;
        }

        $mapping = [
            12 => 1,
            13 => 2,
            14 => 3,
            15 => 4,
            16 => 5,
        ];

        return $mapping[$idLembaga] ?? null;
    }

    public function syncFromApi(SSOClient $client)
    {
        $created   = 0;
        $updated   = 0;
        $unchanged = 0;
        $totalApi  = 0;
        $errors    = [];

        foreach (range(12, 16) as $idLembaga) {
            try {
                $items = $client->getKaryawanByLembaga($idLembaga);

                $totalApi += count($items);

                foreach ($items as $item) {
                    $idLembagaItem = isset($item['id_lembaga'])
                        ? (int) $item['id_lembaga']
                        : $idLembaga;

                    $fakultasId = $this->mapLembagaToFakultas($idLembagaItem);

                    $data = [
                        'fakultas_id'   => $fakultasId,
                        'prodi_id'      => null,

                        'nama_penduduk' => $item['nama_penduduk'] ?? '-',
                        'nidn'          => $item['nidn']          ?? '-',
                        'email'         => $item['email']         ?? null,
                        'no_hp'         => $item['no_hp']         ?? null,
                    ];

                    $penduduk = Penduduk::where('id_penduduk', $item['id_penduduk'])->first();

                    if (! $penduduk) {
                        Penduduk::create(array_merge([
                            'id_penduduk' => $item['id_penduduk'],
                        ], $data));

                        $created++;
                    } else {
                        $penduduk->fill($data);

                        if ($penduduk->isDirty()) {
                            $penduduk->save();
                            $updated++;
                        } else {
                            $unchanged++;
                        }
                    }
                }
            } catch (\Throwable $e) {
                $errors[] = "Lembaga {$idLembaga}: " . $e->getMessage();
            }
        }

        $processed = $created + $updated + $unchanged;

        if ($processed === 0) {
            return redirect()->route('admin.penduduk.index')->with('failed', 'Sinkron data penduduk gagal. ' . implode(' | ', $errors));
        }

        $message = "Sinkron data penduduk selesai. "
            . "Baru: {$created}, diupdate: {$updated}, tidak berubah: {$unchanged}. "
            . "Total data dari API yang berhasil diproses: {$processed}.";

        if (!empty($errors)) {
            $message .= ' Beberapa lembaga gagal diproses: ' . implode(' | ', $errors);

            return redirect()->route('admin.penduduk.index')->with('success', $message);
        }

        return redirect()->route('admin.penduduk.index')->with('success', $message);
    }


    public function show(string $id)
    {
        $data     = Penduduk::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.penduduk.show', compact('data', 'fakultas', 'prodi'));
    }
}

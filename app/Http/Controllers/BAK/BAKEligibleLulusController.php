<?php

namespace App\Http\Controllers\BAK;

use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\MahasiswaEligibleLulus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaEligibleLulusImport;
use Yajra\DataTables\Facades\DataTables;

class BAKEligibleLulusController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;
        $listProdi = collect();
        
        if ($fakultasId) {
            $listProdi = Prodi::where('fakultas_id', $fakultasId)->get();
        }
        
        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)
            ->select('nim', 'nama')
            ->orderBy('nama', 'asc')
            ->get();

        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('bak.eligible_lulus.index', compact('listProdi', 'listTahunAkademik', 'currentTahunAkademik', 'mahasiswa'));
    }

    
    public function getData(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;
        $query = MahasiswaEligibleLulus::with(['mahasiswa.prodi', 'akademik', 'addedByUser'])
            ->where('fakultas_id', $fakultasId);

        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('tahun_akademik_filter')) {
            $query->where('akademik_id', $request->input('tahun_akademik_filter'));
        }

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->filterColumn('nama_mahasiswa', function ($query, $keyword) {
                $query->whereHas('mahasiswa', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('prodi', function ($query, $keyword) {
                $query->whereHas('mahasiswa.prodi', function ($q) use ($keyword) {
                    $q->where('nama_prodi', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->mahasiswa?->nama ?? $row->nim;
            })
            ->addColumn('prodi', function ($row) {
                return $row->mahasiswa?->prodi?->nama_prodi ?? '-';
            })
            ->addColumn('tahun_akademik', function ($row) {
                return $row->akademik?->tahun_akademik ?? '-';
            })
            ->addColumn('ditambahkan_oleh', function ($row) {
                return $row->addedByUser?->nama ?? '-';
            })
            ->addColumn('tanggal_ditambahkan', function ($row) {
                return Carbon::parse($row->updated_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') ?? '—';
            })
            ->addColumn('judul_penelitian', function ($row) {
                return $row->judul_penelitian ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="text-center">
                    <a href="javascript:void(0)" onclick="confirmDelete(' . $row->id . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" 
                        data-bs-toggle="tooltip" data-bs-title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $request->validate([
            'nim'              => 'required|string',
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'judul_penelitian' => 'nullable|string',
        ]);

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        
        $mahasiswa = Mahasiswa::where('nim', $request->nim)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'NIM tidak ditemukan atau bukan mahasiswa fakultas Anda.');
        }

        
        $exists = MahasiswaEligibleLulus::where('nim', $request->nim)
            ->where('akademik_id', $request->akademik_id)
            ->exists();

        if ($exists) {
            return back()->with('failed', 'Mahasiswa dengan NIM ' . $request->nim . ' sudah terdaftar pada tahun akademik ini.');
        }

        MahasiswaEligibleLulus::create([
            'nim'              => $request->nim,
            'fakultas_id'      => $fakultasId,
            'akademik_id'      => $request->akademik_id,
            'added_by'         => $user->id,
            'judul_penelitian' => $request->judul_penelitian,
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke daftar mahasiswa lulusan.');
    }

    
    public function import(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $request->validate([
            'file'        => 'required|mimes:xlsx,xls',
            'akademik_id' => 'required|exists:tahun_akademik,id_akademik',
        ]);

        $fakultasId = $user->penduduk?->fakultas_id;
        if (!$fakultasId) {
            return back()->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        $import = new MahasiswaEligibleLulusImport(
            $fakultasId,
            $request->akademik_id,
            $user->id
        );

        Excel::import($import, $request->file('file'));

        $message = "Import selesai! Baru: {$import->imported}, Diperbarui: {$import->updated}, Dilewati (duplikat): {$import->skipped}, Gagal: {$import->failed}.";

        if (!empty($import->errors)) {
            $errorList = implode(' | ', array_slice($import->errors, 0, 5));
            $message .= " Detail error: " . $errorList;
            if (count($import->errors) > 5) {
                $message .= " ... dan " . (count($import->errors) - 5) . " error lainnya.";
            }
        }

        if ($import->imported > 0 || $import->updated > 0) {
            return back()->with('success', $message);
        }

        return back()->with('failed', $message);
    }

    
    public function destroy(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $eligible = MahasiswaEligibleLulus::findOrFail($id);
        $fakultasId = $user->penduduk?->fakultas_id;
        
        if ($eligible->fakultas_id !== $fakultasId) {
            abort(403);
        }

        $eligible->delete();

        return response()->json(['success' => true, 'message' => 'Data mahasiswa lulusan berhasil dihapus.']);
    }

    
    public function downloadTemplate()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'nim');
        $sheet->setCellValue('B1', 'nama');
        $sheet->setCellValue('C1', 'judul_penelitian');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        
        $sheet->setCellValue('A2', '2021001001');
        $sheet->setCellValue('B2', 'Ahmad Ridho');
        $sheet->setCellValue('C2', 'Sistem Informasi A');
        $sheet->setCellValue('A3', '2021001002');
        $sheet->setCellValue('B3', 'Siti Aminah');
        $sheet->setCellValue('C3', 'Pengembangan Web B');

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $fileName = 'template_mahasiswa_lulusan.xlsx';
        $tempPath = storage_path('app/' . $fileName);

        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}

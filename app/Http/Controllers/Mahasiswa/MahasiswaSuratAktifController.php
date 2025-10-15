<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Template;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Http\Controllers\Controller;
use App\Models\HistoryPengajuan;
use Illuminate\Support\Facades\Auth;

class MahasiswaSuratAktifController extends Controller
{
    public function create()
    {
        $user     = Auth::user();
        $akademik = TahunAkademik::all();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        return view('mahasiswa.surat_aktif.create', compact('akademik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'            => 'required|in:UMUM,PNS,PPPK',
            'semester'            => 'required',
            'akademik_id'         => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'           => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir' => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'             => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'            => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'          => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'              => 'required_if:kategori,PNS,PPPK|nullable',
        ]);

        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $kategoriToTemplate = [
            'UMUM'  => 'surat_aktif_umum',
            'PNS'   => 'surat_aktif_pns',
            'PPPK'  => 'surat_aktif_pppk',
        ];

        $namaTemplate = $kategoriToTemplate[$request->kategori];

        // Cari template berdasarkan kategori + fakultas
        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk kategori {$request->kategori} belum tersedia untuk fakultas Anda.");
        }

        // Generate nomor surat
        $noSurat = SuratAktif::getNextNoSurat($template->id_template);

        $surat = SuratAktif::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'semester'            => $request->semester,
            'kategori'            => $request->kategori,
            'nama_ortu'           => $request->nama_ortu,
            'nip'                 => $request->nip,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pangkat'             => $request->pangkat,
            'golongan'            => $request->golongan,
            'tmt'                 => $request->tmt,
            'unit_kerja'          => $request->unit_kerja,
            'alamat'              => $request->alamat,
            'status'              => 'pengajuan'
        ]);

        HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'tabel'          => $namaTemplate,
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

        return redirect()->route('mahasiswa.history.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TemplateControler extends Controller
{
    
    public function index()
    {
        $data = Template::with(['fakultas', 'prodi'])->get();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();

        return view('admin.template.index', compact('data', 'fakultas', 'prodi'));
    }

    public function getTemplate(Request $request)
    {
        $data = Template::select(['id_template', 'nama_template', 'jenis_surat', 'file', 'tgl_sk', 'fakultas_id', 'prodi_id'])
            ->with('fakultas', 'prodi');

        if ($request->has('fakultas_filter') && $request->fakultas_filter != '') {
            $data->where('fakultas_id', $request->fakultas_filter);
        }

        return DataTables::of($data)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('file', function ($template) {
                if (!$template->file || !Storage::disk('local')->exists($template->file)) {
                    return '<span class="text-muted">File tidak ditemukan</span>';
                }
                $url = route('admin.template.download', $template->id_template);

                $extension = strtolower(pathinfo($template->file, PATHINFO_EXTENSION));

                $icon = (in_array($extension, ['doc', 'docx'])) ? '<i class="fas fa-file-word fa-2x"></i>' : '<i class="fas fa-file fa-2x"></i>';
                $color = 'text-primary';

                return '<a href="' . e($url) . '" target="_blank" title="Unduh file">' .
                    '<span class="' . $color . '">' . $icon . '</span>' .
                    '</a>';
            })
            ->editColumn('tgl_sk', function ($row) {
                if (empty($row->tgl_sk)) {
                    return '—';
                }
                return Carbon::parse($row->tgl_sk)->locale('id')->isoFormat('D MMMM YYYY');
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('action', function ($row) {
                $nama = htmlspecialchars($row->nama_template ?? '-');
                $jenis = htmlspecialchars($row->jenis_surat ?? '-');
                $fakultas_id = $row->fakultas_id ?? '';
                $prodi_id = $row->prodi_id ?? '';
                $tgl_sk = $row->tgl_sk ?? '';
                $nama_fakultas = htmlspecialchars($row->fakultas ? $row->fakultas->nama_fakultas : '-');
                $nama_prodi = htmlspecialchars($row->prodi ? $row->prodi->nama_prodi : '-');
                $file = $row->file ? true : false;
                $downloadUrl = $file ? route('admin.template.download', $row->id_template) : '';

                $showBtn = '<a href="javascript:void(0)" onclick="showModal(this)" data-nama="'.$nama.'" data-jenis="'.$jenis.'" data-fakultas="'.$nama_fakultas.'" data-prodi="'.$nama_prodi.'" data-tgl="'.$tgl_sk.'" data-file="'.$file.'" data-download="'.$downloadUrl.'" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="javascript:void(0)" onclick="editModal(this)" data-id="'.$row->id_template.'" data-nama="'.$nama.'" data-jenis="'.$jenis.'" data-fakultas="'.$fakultas_id.'" data-prodi="'.$prodi_id.'" data-tgl="'.$tgl_sk.'" data-file="'.$file.'" data-download="'.$downloadUrl.'" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id_template . '" class="btn btn-sm btn-light btn-active-light-danger text-center delete-btn" data-bs-toggle="tooltip" data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['file', 'tgl_sk', 'nama_fakultas', 'action'])
            ->make(true);
    }

    public function downloadTemplate($id)
    {
        $template = Template::findOrFail($id);

        $filePath = $template->file;

        if (Storage::disk('local')->exists($filePath)) {

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $downloadFileName = "Template_" . $template->nama_template . "." . $extension;

            return Storage::download($filePath, $downloadFileName);
        }

        abort(404, "File template tidak ditemukan.");
    }

    public function getProdi($fakultas_id)
    {
        $prodi = Prodi::where('fakultas_id', $fakultas_id)->get();

        return response()->json($prodi);
    }

    
    public function create()
    {
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();
        return view('admin.template.create', compact('fakultas', 'prodi'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required',
            'jenis_surat'   => 'required',
            'file'          => 'required|mimes:doc,docx|max:10240',
            'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
            'prodi_id'      => 'nullable|exists:prodi,id_prodi',
        ], [
            'nama_template.required' => 'Nama template wajib diisi',
            'jenis_surat.required'   => 'Jenis surat wajib diisi',
            'file.required'          => 'File template wajib diisi',
            'file.mimes'             => 'File harus berformat .doc atau .docx',
            'file.max'               => 'Ukuran file maksimal 10MB',
            'fakultas_id.required'   => 'Fakultas harus diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $jenisSuratName = $request->jenis_surat;
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->fakultas_id . '/' . $jenisSuratName . '.' . $extension;
            $filePath = $file->storeAs('templates', $fileName);
        }

        Template::create([
            'nama_template' => $request->nama_template,
            'jenis_surat'   => $request->jenis_surat,
            'file'          => $filePath,
            'tgl_sk'        => $request->tgl_sk,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('admin.template.index'),
            ], 201);
        }

        return redirect()->route('admin.template.index')->with('success', 'Data berhasil ditambahkan!');
    }

    
    public function show(string $id)
    {
        $data = Template::with(['fakultas', 'prodi'])->findOrFail($id);

        return view('admin.template.show', compact('data'));
    }

    
    public function edit(string $id)
    {
        $data     = Template::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.template.edit', compact('data', 'fakultas', 'prodi'));
    }

    
    public function update(Request $request, string $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'nama_template' => 'required',
            'jenis_surat'   => 'required',
            'file'          => 'nullable|mimes:doc,docx|max:10240',
            'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
            'prodi_id'      => 'nullable|exists:prodi,id_prodi',
        ], [
            'nama_template.required' => 'Nama template wajib diisi',
            'jenis_surat.required'   => 'Jenis surat wajib diisi',
            'file.mimes'             => 'File harus berformat .doc atau .docx',
            'file.max'               => 'Ukuran file maksimal 10MB',
            'fakultas_id.required'   => 'Fakultas harus diisi.',
        ]);

        $filePath = $template->file; 
        if ($request->hasFile('file')) {
            
            if ($template->file && Storage::disk('local')->exists($template->file)) {
                Storage::disk()->delete($template->file);
            }

            $file     = $request->file('file');
            $jenisSuratName = $request->jenis_surat;
            $extension = $file->getClientOriginalExtension();
            $fileName = $request->fakultas_id . '/' . $jenisSuratName . '.' . $extension;
            $filePath = $file->storeAs('templates', $fileName);
        }

        $template->update([
            'nama_template' => $request->nama_template,
            'jenis_surat'   => $request->jenis_surat,
            'file'          => $filePath,
            'tgl_sk'        => $request->tgl_sk,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message'  => 'OK',
                'redirect' => route('admin.template.index'),
            ], 200);
        }

        return redirect()->route('admin.template.index')->with('success', 'Data berhasil diupdate!');
    }

    
    public function destroy(string $id)
    {
        $data = Template::findOrFail($id);
        
        if ($data->file && Storage::disk('local')->exists($data->file)) {
            Storage::disk()->delete($data->file);
        }
        $data->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TemplateControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Template::with(['fakultas', 'prodi'])->get();

        return view('admin.template.index', compact('data'));
    }

    public function getTemplate()
    {
        $data = Template::select(['id_template', 'nama_template', 'file', 'fakultas_id', 'prodi_id'])
            ->with('fakultas', 'prodi');

        return DataTables::of($data)
            ->addColumn('file', function ($template) {
                if (!$template->file || !Storage::disk('public')->exists($template->file)) {
                    return '<span class="text-muted">File tidak ditemukan</span>';
                }

                $url = asset('storage/' . $template->file);
                $extension = strtolower(pathinfo($template->file, PATHINFO_EXTENSION));

                $icon = '<i class="fas fa-file fa-2x"></i>';
                $color = 'text-primary';

                if (in_array($extension, ['doc', 'docx'])) {
                    $icon = '<i class="fas fa-file-word fa-2x"></i>';
                    $color = 'text-primary';
                }

                // Opsi 1: Buka langsung
                $link = $url;

                // Opsi 2 (opsional): Preview Word via Google Docs
                // if (in_array($extension, ['doc', 'docx'])) {
                //     $link = "https://docs.google.com/gview?url=" . urlencode($url) . "&embedded=true";
                // }

                return '<a href="' . e($link) . '" target="_blank" title="Lihat file">' .
                    '<span class="' . $color . '">' . $icon . '</span>' .
                    '</a>';
            })
            ->addColumn('nama_fakultas', function ($row) {
                return $row->fakultas ? $row->fakultas->nama_fakultas : '—';
            })
            ->addColumn('nama_prodi', function ($row) {
                return $row->prodi ? $row->prodi->nama_prodi : '—';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.template.show', $row->id_template) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.template.edit', $row->id_template) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id_template . '" class="btn btn-sm btn-light btn-active-light-danger text-center delete-btn" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['file', 'nama_fakultas', 'nama_prodi', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();
        return view('admin.template.create', compact('fakultas', 'prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_template' => 'required',
            'file'          => 'required|mimes:doc,docx|max:10240',
            'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
            'prodi_id'      => 'nullable|exists:prodi,id_prodi',
        ], [
            'nama_template.required' => 'Nama template wajib diisi',
            'file.required'          => 'File template wajib diisi',
            'file.mimes'             => 'File harus berformat .doc atau .docx',
            'file.max'               => 'Ukuran file maksimal 10MB',
            'fakultas_id.required'   => 'Fakultas harus diisi.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates', $fileName, 'public');
        }

        Template::create([
            'nama_template' => $request->nama_template,
            'file'          => $filePath,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        return redirect()->route('admin.template.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Template::with(['fakultas', 'prodi'])->findOrFail($id);

        return view('admin.template.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data     = Template::findOrFail($id);
        $fakultas = Fakultas::all();
        $prodi    = Prodi::all();

        return view('admin.template.edit', compact('data', 'fakultas', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'nama_template' => 'required',
            'file'          => 'nullable|mimes:doc,docx|max:10240',
            'fakultas_id'   => 'required|exists:fakultas,id_fakultas',
            'prodi_id'      => 'nullable|exists:prodi,id_prodi',
        ], [
            'nama_template.required' => 'Nama template wajib diisi',
            'file.mimes'             => 'File harus berformat .doc atau .docx',
            'file.max'               => 'Ukuran file maksimal 10MB',
            'fakultas_id.required'   => 'Fakultas harus diisi.',
        ]);

        $filePath = $template->file; // Simpan path file lama
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($template->file && Storage::disk('public')->exists($template->file)) {
                Storage::disk('public')->delete($template->file);
            }

            $file     = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates', $fileName, 'public');
        }

        $template->update([
            'nama_template' => $request->nama_template,
            'file'          => $filePath,
            'fakultas_id'   => $request->fakultas_id,
            'prodi_id'      => $request->prodi_id,
        ]);

        return redirect()->route('admin.template.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Template::findOrFail($id);
        // Hapus file dari storage jika ada
        if ($data->file && Storage::disk('public')->exists($data->file)) {
            Storage::disk('public')->delete($data->file);
        }
        $data->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}

@extends('layout.main')
@section('title', 'Surat Permohonan PKL')
@section('content')
    @php
        $mahasiswaOptions = $mahasiswa->map(fn($mhs) => ['nim' => $mhs->nim, 'nama' => $mhs->nama, 'prodi' => $mhs->prodi?->nama_prodi ?? '-'])->values();
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Surat Permohonan PKL</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST" action="{{ route('bak.surat-pkl.update', $surat->id_surat_pkl) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Mahasiswa Pengaju</label>
                                                <select class="form-select form-select-sm select2-hidden-accessible w-100" data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim" id="mahasiswa_pengaju_select" tabindex="-1" required>
                                                    <option value="">Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->nim }}" data-nama="{{ $mhs->nama }}" data-prodi="{{ $mhs->prodi?->nama_prodi ?? '-' }}" {{ old('nim', $surat->nim) == $mhs->nim ? 'selected' : '' }}>{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('nim')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                <input type="hidden" name="akademik_id" value="{{ $latestAkademik?->id_akademik }}">
                                                @error('akademik_id')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt fs-5"></i></span>
                                                    <input id="tgl_mulai" type="text" name="tgl_mulai" class="form-control form-control-sm" placeholder="Pilih tanggal mulai" autocomplete="off" value="{{ old('tgl_mulai', $surat->tgl_mulai ? $surat->tgl_mulai->format('Y-m-d') : '') }}" required />
                                                </div>
                                                @error('tgl_mulai')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt fs-5"></i></span>
                                                    <input id="tgl_selesai" type="text" name="tgl_selesai" class="form-control form-control-sm" placeholder="Pilih tanggal selesai" autocomplete="off" value="{{ old('tgl_selesai', $surat->tgl_selesai ? $surat->tgl_selesai->format('Y-m-d') : '') }}" required />
                                                </div>
                                                @error('tgl_selesai')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tempat PKL</label>
                                                <select class="form-select form-select-sm select2-hidden-accessible w-100" data-control="select2" data-placeholder="Pilih Tempat PKL" name="mitra_id" tabindex="-1" required>
                                                    <option value="">Pilih Tempat PKL...</option>
                                                    @foreach ($mitra as $m)
                                                        <option value="{{ $m->id_mitra }}" {{ old('mitra_id', $surat->mitra_id) == $m->id_mitra ? 'selected' : '' }}>{{ $m->nama_mitra }}</option>
                                                    @endforeach
                                                </select>
                                                @error('mitra_id')<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                        @include('bak.surat_pkl._anggota_kelompok', ['anggotaKelompok' => old('anggota_kelompok', $surat->anggota_kelompok ?? []), 'mahasiswa' => $mahasiswa])
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">Update Pengajuan</span>
                                            <span class="indicator-progress">Tunggu sebentar...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kt_ecommerce_settings_general_form');
            if (!form) return;
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const mulaiEl = document.getElementById('tgl_mulai');
            const selesaiEl = document.getElementById('tgl_selesai');
            const pengajuSelect = document.getElementById('mahasiswa_pengaju_select');
            const anggotaContainer = document.getElementById('anggota-kelompok-container');
            const mahasiswaOptions = @json($mahasiswaOptions);
            let anggotaIndex = anggotaContainer ? anggotaContainer.querySelectorAll('tr').length : 0;

            const fpMulai = flatpickr(mulaiEl, { dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", altInputClass: "form-control form-control-sm", allowInput: true, defaultDate: mulaiEl.value || null, maxDate: selesaiEl.value || null, onReady: function(s,d,i){ if(i.altInput){i.altInput.required=true; i.altInput.placeholder=mulaiEl.placeholder||'';} }, onChange: function(s,d){ fpSelesai.set("minDate", d||null); } });
            const fpSelesai = flatpickr(selesaiEl, { dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", altInputClass: "form-control form-control-sm", allowInput: true, defaultDate: selesaiEl.value || null, minDate: mulaiEl.value || null, onReady: function(s,d,i){ if(i.altInput){i.altInput.required=true; i.altInput.placeholder=selesaiEl.placeholder||'';} }, onChange: function(s,d){ fpMulai.set("maxDate", d||null); } });

            function initSelect2(element) {
                if (!element || !window.jQuery || !jQuery.fn.select2) return;
                const $el = jQuery(element);
                if ($el.data('select2')) $el.select2('destroy');
                $el.select2({ width: '100%', placeholder: element.dataset.placeholder || 'Pilih mahasiswa' });
                if ($el.hasClass('anggota-nim-select')) {
                    $el.off('.anggotaAdminSync');
                    $el.on('change.anggotaAdminSync select2:select.anggotaAdminSync select2:clear.anggotaAdminSync', function() { syncAnggotaRow(element.closest('tr')); });
                }
            }
            function buildMahasiswaOptions(selectedNim) {
                let html = '<option value="">Pilih mahasiswa...</option>';
                mahasiswaOptions.forEach(function(item) { html += '<option value="' + item.nim + '" data-nama="' + item.nama + '" data-prodi="' + item.prodi + '"' + (selectedNim === item.nim ? ' selected' : '') + '>' + item.nim + ' - ' + item.nama + '</option>'; });
                return html;
            }
            function buildAnggotaRow(index) {
                return '<tr><td><select name="anggota_kelompok[' + index + '][nim]" class="form-select form-select-sm anggota-nim-select" data-control="select2" data-placeholder="Pilih mahasiswa">' + buildMahasiswaOptions('') + '</select><div class="invalid-feedback anggota-nim-feedback"></div></td><td><input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input" placeholder="Nama mahasiswa" disabled /><input type="hidden" name="anggota_kelompok[' + index + '][nama]" class="anggota-nama-hidden-input" /></td><td><input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input" placeholder="Prodi mahasiswa" disabled /><input type="hidden" name="anggota_kelompok[' + index + '][prodi]" class="anggota-prodi-hidden-input" /></td><td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-anggota-kelompok" title="Hapus anggota"><i class="fas fa-trash-alt"></i></button></td></tr>';
            }
            function fillAnggotaRow(row, data) { data = data || {}; row.querySelector('.anggota-nama-input').value = data.nama || ''; row.querySelector('.anggota-prodi-input').value = data.prodi || ''; row.querySelector('.anggota-nama-hidden-input').value = data.nama || ''; row.querySelector('.anggota-prodi-hidden-input').value = data.prodi || ''; }
            function setAnggotaError(row, msg) { const s = row.querySelector('.anggota-nim-select'); const f = row.querySelector('.anggota-nim-feedback'); s.classList.toggle('is-invalid', Boolean(msg)); f.textContent = msg || ''; }
            function syncAnggotaRow(row) { const s = row.querySelector('.anggota-nim-select'); const o = s.options[s.selectedIndex]; setAnggotaError(row); if (!o || !o.value) { fillAnggotaRow(row); return; } fillAnggotaRow(row, { nama: o.dataset.nama || '', prodi: o.dataset.prodi || '' }); }
            function validateAnggotaRows() {
                if (!anggotaContainer) return true;
                const ketuaNim = pengajuSelect ? pengajuSelect.value : '';
                const selected = new Set(); let valid = true;
                anggotaContainer.querySelectorAll('tr').forEach(function(row) { const s = row.querySelector('.anggota-nim-select'); const nim = s.value; syncAnggotaRow(row); if (!nim) return; if (ketuaNim && nim === ketuaNim) { setAnggotaError(row, 'Mahasiswa anggota tidak boleh sama dengan ketua pengaju.'); valid = false; return; } if (selected.has(nim)) { setAnggotaError(row, 'Mahasiswa anggota tidak boleh duplikat.'); valid = false; return; } selected.add(nim); });
                return valid;
            }
            window.addAnggotaKelompokBakRow = function() { if (!anggotaContainer) return; anggotaContainer.insertAdjacentHTML('beforeend', buildAnggotaRow(anggotaIndex)); const row = anggotaContainer.lastElementChild; anggotaIndex += 1; initSelect2(row.querySelector('.anggota-nim-select')); syncAnggotaRow(row); };

            if (pengajuSelect) initSelect2(pengajuSelect);
            if (anggotaContainer) {
                anggotaContainer.querySelectorAll('.anggota-nim-select').forEach(function(s) { initSelect2(s); syncAnggotaRow(s.closest('tr')); });
                anggotaContainer.addEventListener('change', function(e) { const s = e.target.closest('.anggota-nim-select'); if (s) syncAnggotaRow(s.closest('tr')); });
                anggotaContainer.addEventListener('click', function(e) { const b = e.target.closest('.remove-anggota-kelompok'); if (b) { const r = b.closest('tr'); if (r) r.remove(); } });
            }
            form.addEventListener('submit', function(event) { if (!form.checkValidity()) return; if (!validateAnggotaRows()) { event.preventDefault(); return; } submitButton.disabled = true; submitButton.querySelector('.indicator-label').style.display = 'none'; submitButton.querySelector('.indicator-progress').style.display = 'inline-block'; });
        });
    </script>
    @if ($message = Session::get('success'))<script>Swal.fire({text:@json($message),icon:"success",buttonsStyling:false,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}});</script>@endif
    @if ($message = Session::get('failed'))<script>Swal.fire({text:@json($message),icon:"error",buttonsStyling:false,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-danger"}});</script>@endif
@endsection

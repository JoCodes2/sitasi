@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">

    <x-base-header title="Finalisasi Dosen Pembimbing" icon="fa-solid fa-file-signature">
        {{-- Header tanpa tombol tambah karena data berasal dari proses plotting --}}
        <div class="badge bg-primary px-3 py-2">
            <i class="fa-solid fa-check-double me-1"></i> Tahap Finalisasi
        </div>
    </x-base-header>

    <x-base-body>
       <div class="alert alert-light border-primary shadow-sm small mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info fa-2x text-primary me-3"></i>
                <div>
                    @if(auth()->user()->role === 'super-admin' || auth()->user()->role === 'admin')
                        {{-- Deskripsi untuk Admin/Super Admin --}}
                        <span class="fw-bold d-block">Konfirmasi Pembimbing & Publikasi</span>
                        <p class="mb-0 text-muted">
                            Tinjau kembali hasil plotting otomatis. Anda dapat menyesuaikan dosen pembimbing sebelum menekan tombol <b>Publish</b>.
                        </p>
                    @else
                        {{-- Deskripsi untuk Mahasiswa --}}
                        <span class="fw-bold d-block">Status Plotting Pembimbing</span>
                        <p class="mb-0 text-muted">
                            Berikut adalah hasil penetapan dosen pembimbing Anda. Jika status masih <b>Draft</b>, harap tunggu hingga admin melakukan publikasi resmi.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        @php
            // Cek apakah user adalah super_admin
            $isSuperAdmin = auth()->user()->role === 'admin';

            // Susun header secara dinamis
            $headers = ['No', 'Informasi Mahasiswa', 'Informasi Judul', 'Pembimbing 1', 'Pembimbing 2','Status'];

            if ($isSuperAdmin) {
                $headers[] = 'Aksi';
            }
        @endphp

        <x-base-table :headers="$headers" id="finalisasiTable">
            <tbody id="finalisasiBody">
            </tbody>
        </x-base-table>
    </x-base-body>
</div>

{{-- Bagian Modal Edit Dosen Pembimbing --}}
<x-base-modal
    id="modalEditPlotting"
    title="Edit Dosen Pembimbing"
    btnId="btnSimpanPlotting"
    btnText="Update Pembimbing"
>
    <form id="formEditPlotting">
        @csrf
        <input type="hidden" name="id" id="id_pengajuan">

        <div class="mb-4 text-center">
            <span class="d-block small text-muted">Mahasiswa</span>
            <h6 id="display_nama_mhs" class="fw-bold text-primary">-</h6>
            <hr class="my-2">
        </div>

        <div class="mb-3">
            <label for="dosen_pembimbing_1_id" class="form-label fw-bold">Dosen Pembimbing 1 (Lektor/Utama)</label>
            <select class="form-control select2-modal" id="dosen_pembimbing_1_id" name="dosen_pembimbing_1_id">
                <option value="">-- Pilih Dosen Pembimbing 1 --</option>
                {{-- Data Dosen di-load via JS --}}
            </select>
            <small class="text-danger error-msg" id="error-dosen_pembimbing_1_id"></small>
        </div>

        <div class="mb-3">
            <label for="dosen_pembimbing_2_id" class="form-label fw-bold">Dosen Pembimbing 2 (Kepakaran)</label>
            <select class="form-control select2-modal" id="dosen_pembimbing_2_id" name="dosen_pembimbing_2_id">
                <option value="">-- Pilih Dosen Pembimbing 2 --</option>
                {{-- Data Dosen di-load via JS --}}
            </select>
            <small class="text-danger error-msg" id="error-dosen_pembimbing_2_id"></small>
        </div>
    </form>
</x-base-modal>
@endsection

@section('scripts')
<script>
    window.AUTH_USER_ID = "{{ auth()->id() }}";
</script>
<script type="module" src="{{ asset('controllers/finalisasi-dosen.controller.js') }}"></script>
@endsection

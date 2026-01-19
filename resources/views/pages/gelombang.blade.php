@extends('Layouts.Base')

@section('content')
<div class="card">

    <x-base-header title="Gelombang Pengajuan" icon="fa-solid fa-calendar-days">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnTambah">
                <i class="fa fa-plus"></i> Tambah Gelombang
            </button>
        </div>
    </x-base-header>

    <x-base-body>
        <div class="alert alert-secondary border-0 small mb-4">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Halaman ini digunakan untuk mengatur periode pembukaan pengajuan judul skripsi bagi mahasiswa.
        </div>

        @php
            $headers = ['No', 'Tahun Ajaran', 'Semester', 'Gelombang', 'Mulai', 'Selesai', 'Status', 'Aksi'];
        @endphp

        <x-base-table :headers="$headers" id="gelombangTable">
            <tbody id="gelombangBody">

            </tbody>
        </x-base-table>
    </x-base-body>
</div>
{{-- Bagian Modal --}}
<x-base-modal
    id="modalTambahGelombang"
    title="Tambah Gelombang Baru"
    btnId="btnSimpanGelombang"
    btnText="Simpan Gelombang"
>
    <form id="formGelombang">
        @csrf
        <input type="hidden" name="id" id="id">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tahun_ajaran" class="form-label font-weight-bold">Tahun Ajaran</label>
                <input type="text" class="form-control" id="tahun_ajaran" name="tahun_ajaran" placeholder="Contoh: 2025/2026">
                <small class="text-danger error-msg" id="error-tahun_ajaran"></small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="semester" class="form-label font-weight-bold">Semester</label>
                <select class="form-control" id="semester" name="semester">
                    <option value="">-- Pilih Semester --</option>
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
                <small class="text-danger error-msg" id="error-semester"></small>
            </div>
        </div>

        <div class="mb-3">
            <label for="gelombang_ke" class="form-label font-weight-bold">Gelombang Ke-</label>
            <input type="number" class="form-control" id="gelombang_ke" name="gelombang_ke" placeholder="1">
            <small class="text-danger error-msg" id="error-gelombang_ke"></small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tgl_mulai" class="form-label font-weight-bold">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai">
                <small class="text-danger error-msg" id="error-tgl_mulai"></small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tgl_selesai" class="form-label font-weight-bold">Tanggal Selesai</label>
                <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai">
                <small class="text-danger error-msg" id="error-tgl_selesai"></small>
            </div>
        </div>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_aktif" name="is_aktif" value="1">
            <label class="form-check-label" for="is_aktif">Aktifkan Gelombang Ini</label>
        </div>
    </form>
</x-base-modal>
@endsection
@section('scripts')
<script type="module" src="{{ asset('controllers/gelombang.controller.js') }}"></script>
@endsection

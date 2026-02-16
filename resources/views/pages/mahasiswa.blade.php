@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">
    {{-- Header dengan Icon Mahasiswa dan Tombol Aksi di Kanan --}}
    <x-base-header title="Data Mahasiswa" icon="fa-solid fa-user-graduate">
    </x-base-header>

    <x-base-body>
        {{-- Alert dengan design yang lebih clean --}}
        <div class="alert alert-light border-left-primary shadow-sm small mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-info fa-2x text-primary mr-3"></i>
                <div>
                    <span class="font-weight-bold d-block">Daftar mahasiswa aktif yang terdaftar.</span>
                </div>
            </div>
        </div>

        @php
            $headers = ['No', 'Informasi Mahasiswa', 'NIM', 'Program Studi', 'Angkatan', 'Aksi'];
        @endphp

        <x-base-table :headers="$headers" id="mahasiswaTable">
            <tbody id="mahasiswaBody">
                {{-- Data akan dirender oleh JavaScript --}}
            </tbody>
        </x-base-table>
    </x-base-body>
</div>
<x-base-modal
    id="modalDetailMahasiswa"
    title="Detail Informasi Mahasiswa"
    size="modal-lg"
>
    {{-- Tab Navigasi --}}
    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="pills-biodata-tab" data-bs-toggle="pill" data-bs-target="#pills-biodata" type="button" role="tab"><i class="fa-solid fa-address-card me-1"></i> Biodata</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="pills-akun-tab" data-bs-toggle="pill" data-bs-target="#pills-akun" type="button" role="tab"><i class="fa-solid fa-user-lock me-1"></i> Akun & Kontak</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        {{-- Pane Biodata --}}
        <div class="tab-pane fade show active" id="pills-biodata" role="tabpanel">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="text-muted small d-block">Nama Lengkap</label>
                    <p id="det_nama" class="fw-bold text-dark border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">NIM</label>
                    <p id="det_nim" class="fw-bold text-primary border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Tempat, Tanggal Lahir</label>
                    <p id="det_ttl" class="fw-bold border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Agama</label>
                    <p id="det_agama" class="fw-bold border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Jenis Kelamin</label>
                    <p id="det_jk" class="fw-bold border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Program Studi</label>
                    <p id="det_prodi" class="fw-bold border-bottom pb-1">-</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small d-block">Angkatan</label>
                    <p id="det_angkatan" class="fw-bold border-bottom pb-1">-</p>
                </div>
                <div class="col-6">
                    <label class="text-muted small d-block">Alamat Lengkap</label>
                    <p id="det_alamat" class="fw-bold border-bottom pb-1">-</p>
                </div>
            </div>
        </div>

        {{-- Pane Akun --}}
        <div class="tab-pane fade" id="pills-akun" role="tabpanel">
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="fa-solid fa-envelope text-muted me-2"></i> Email System
                    </div>
                    <span id="det_email" class="fw-bold">-</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="fa-solid fa-phone text-muted me-2"></i> WhatsApp / No. HP
                    </div>
                    <span id="det_no_hp" class="fw-bold">-</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <i class="fa-solid fa-shield-halved text-muted me-2"></i> Role Akses
                    </div>
                    <span class="badge bg-soft-primary text-primary text-uppercase px-3">Mahasiswa</span>
                </div>
            </div>
        </div>
    </div>
</x-base-modal>

@endsection
@section('scripts')
<script type="module" src="{{ asset('controllers/mahasiswa.controller.js') }}"></script>
@endsection

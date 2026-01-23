@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">
    <x-base-header title="Data Pengajuan Judul Mahasiswa" icon="fa-solid fa-solid fa-book"></x-base-header>

    <x-base-body  data-is-admin="{{ auth()->user()->role === 'super-admin' || auth()->user()->role === 'admin' ? '1' : '0' }}">
        <x-base-table :headers="['No', 'Tanggal', 'Mahasiswa','Gelombang', 'Prioritas', 'Aksi']" id="judulTable">
            <tbody id="judulBody"></tbody>
        </x-base-table>
    </x-base-body>
</div>

<div class="modal fade" id="modalDetailPengajuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-solid fa-book me-2"></i> Detail Pengajuan Judul
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- LOADER -->
            <div id="loaderModal" class="text-center py-5">
                <div class="spinner-border text-primary"></div>
            </div>

            <!-- BODY -->
            <div class="modal-body d-none" id="contentModal">

                <!-- INFORMASI MAHASISWA -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Informasi Mahasiswa</div>
                    <div class="card-body row">
                        <div class="col-md-4">
                            <label class="fw-bold">Nama</label>
                            <p id="detNama">-</p>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">NIM</label>
                            <p id="detNim">-</p>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold">Angkatan</label>
                            <p id="detAngkatan">-</p>
                        </div>
                    </div>
                </div>

                <!-- INFORMASI GELOMBANG -->
                <div class="card mb-4">
                    <div class="card-header fw-bold">Informasi Gelombang</div>
                    <div class="card-body row">
                        <div class="col-md-3">
                            <label class="fw-bold">Gelombang</label>
                            <p id="detGelombangKe">-</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Semester</label>
                            <p id="detSemester">-</p>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Tahun Ajaran</label>
                            <p id="detTahunAjaran">-</p>
                        </div>
                    </div>
                </div>

                <!-- PRIORITAS -->
                <div class="alert alert-info mb-4">
                    <strong>Prioritas Judul:</strong>
                    <span id="detHarapanJudul">-</span>
                    <hr>
                    <strong>Alasan:</strong>
                    <p id="detAlasan" class="mb-0"></p>
                </div>

                <!-- JUDUL 1 -->
                <div class="border rounded p-4 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="mb-0">Pilihan 1</h5>
                    </div>
                    <p class="fw-bold" id="title-1">-</p>
                    <span class="badge bg-info mb-2" id="topik-1">-</span>
                    <p id="lb-1" class="text-muted"></p>
                </div>

                <!-- JUDUL 2 -->
                <div class="border rounded p-4 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="mb-0">Pilihan 2</h5>
                    </div>
                    <p class="fw-bold" id="title-2">-</p>
                    <span class="badge bg-info mb-2" id="topik-2">-</span>
                    <p id="lb-2" class="text-muted"></p>
                </div>

                <!-- JUDUL 3 -->
                <div class="border rounded p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="mb-0">Pilihan 3</h5>
                    </div>
                    <p class="fw-bold" id="title-3">-</p>
                    <span class="badge bg-info mb-2" id="topik-3">-</span>
                    <p id="lb-3" class="text-muted"></p>
                </div>

            </div>
            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="module" src="{{ asset('controllers/judul.controller.js') }}"></script>
@endsection

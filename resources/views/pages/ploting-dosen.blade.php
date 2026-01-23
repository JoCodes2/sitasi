@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">
    <x-base-header title="Plotting Dosen Pembimbing" icon="fa-solid fa-diagram-project">
        <button class="btn btn-primary btn-sm" id="btnProsesPlotting">
            <i class="fa-solid fa-diagram-project me-1"></i>
            Proses Pembagian Dosen
        </button>
    </x-base-header>

    <x-base-body>

        {{-- INFO --}}
        <div class="alert alert-light border-primary shadow-sm small mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fa-2x text-primary me-3"></i>
                <div>
                    <span class="fw-bold d-block">Pembagian Dosen Pembimbing</span>
                    <p class="mb-0 text-muted">
                        Menampilkan mahasiswa dengan status judul
                        <b>Disetujui (Approved)</b> dan dosen aktif.
                    </p>
                </div>
            </div>
        </div>

        {{-- GRID KIRI & KANAN --}}
        <div class="row g-4">

            {{-- ================= LEFT : MAHASISWA ================= --}}
            <div class="col-md-8">
                <div class="card border shadow-sm h-100">
                    <div class="card-header fw-bold bg-light">
                        <i class="fa-solid fa-user-graduate me-2"></i>
                        Data Mahasiswa & Judul
                    </div>

                    <div class="card-body p-0">
                        <x-base-table
                            :headers="['No', 'Informasi Mahasiswa', 'Informasi Judul', 'Gelombang']"
                            id="mahasiswaTable"
                        >
                            <tbody id="mahasiswaBody">
                                {{-- Render oleh JS --}}
                            </tbody>
                        </x-base-table>
                    </div>
                </div>
            </div>

            {{-- ================= RIGHT : DOSEN ================= --}}
            <div class="col-md-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-header fw-bold bg-light">
                        <i class="fa-solid fa-chalkboard-user me-2"></i>
                        Data Dosen Pembimbing
                    </div>

                    <div class="card-body p-0">
                        <x-base-table
                            :headers="['No', 'Nama Dosen', 'Bidang']"
                            id="dosenTable"
                        >
                            <tbody id="dosenBody">
                                {{-- Render oleh JS --}}
                            </tbody>
                        </x-base-table>
                    </div>
                </div>
            </div>

        </div>

                {{-- ================= HASIL PLOTTING (Akan muncul setelah proses) ================= --}}
        <div id="hasilPlottingContainer" class="d-none mt-4">
            <div class="card shadow-sm border-0 bg-primary bg-opacity-10 mb-4">
                <div class="card-body py-3 px-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-list-check fa-lg text-primary me-2"></i>
                        <div>
                            <span class="fw-bold d-block text-primary">Hasil Pembagian Dosen Otomatis</span>
                            <span class="badge bg-primary" id="totalMhsTerplot">0 Terplot</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm shadow-sm" id="btnFinalisasiPlotting">
                    <i class="fa-solid fa-file-signature me-1"></i>
                    Finalisasi & Simpan Plotting
                </button>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <x-base-table :headers="['No', 'Mahasiswa', 'Pembimbing 1 (Lektor)', 'Pembimbing 2 (Kepakaran)']" id="hasilTable">
                    <tbody id="hasilPlottingBody">
                    </tbody>
                </x-base-table>
            </div>
        </div>
    </x-base-body>
</div>
<div class="modal fade" id="modalHungarian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-calculator me-2"></i>
                    Detail Perhitungan Algoritma Hungarian
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-table me-2"></i>1. Matriks Biaya (Cost Matrix)</h6>
                    <span class="badge bg-info text-dark">Formula: 100 - % Kepakaran</span>
                </div>

                <div class="alert alert-light border small mb-3">
                    <i class="fa-solid fa-circle-info text-info me-2"></i>
                    Sel berwarna <span class="badge bg-success text-white">Hijau</span> adalah hasil penugasan optimal (biaya terendah) yang dipilih sistem.
                </div>

                <div class="table-responsive mb-4" style="max-height: 350px;">
                    <table class="table table-bordered table-sm text-center align-middle" style="font-size: 10px;">
                        <thead class="table-dark sticky-top" id="hungarianHeader"></thead>
                        <tbody id="hungarianBody"></tbody>
                    </table>
                </div>

                <hr>

                <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-check-double me-2"></i>2. Kesimpulan Penempatan Pembimbing</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover border align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th class="text-start">Mahasiswa & Judul</th>
                                <th>Pembimbing 1 (Lektor)</th>
                                <th>Pembimbing 2 (Kepakaran)</th>
                            </tr>
                        </thead>
                        <tbody id="kesimpulanPlottingBody" style="font-size: 13px;"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="module" src="{{ asset('controllers/ploting-dosen.controller.js') }}"></script>
@endsection

@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">
    <x-base-header title="Plotting Dosen Pembimbing" icon="fa-solid fa-users-gear">
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

    </x-base-body>
</div>
@endsection

@section('scripts')
<script type="module" src="{{ asset('controllers/ploting-dosen.controller.js') }}"></script>
@endsection

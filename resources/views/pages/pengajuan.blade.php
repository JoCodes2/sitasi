@extends('Layouts.Base')

@section('content')
<div class="card shadow-sm border-0">
    {{-- Header dengan Icon Mahasiswa dan Tombol Aksi di Kanan --}}
    <x-base-header title="Pengajuan Judul" icon="fa-solid fa-solid fa-file-signature">
    </x-base-header>

    <x-base-body>
        <div id="gelombangContainer"></div>

        {{-- Form pengajuan (awal disembunyikan) --}}
        <div id="formPengajuanContainer" class="d-none">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form id="formPengajuanJudul">
                        @csrf
                        <input type="hidden" name="gelombang_id" id="gelombang_id">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Pilihan Prioritas Judul</label>
                                <select name="harapan_judul" class="form-select">
                                    <option value="">-- Pilih Judul yang Paling Diharapkan --</option>
                                    <option value="1">Judul 1</option>
                                    <option value="2">Judul 2</option>
                                    <option value="3">Judul 3</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Alasan Memilih Prioritas Tersebut</label>
                                <textarea name="alasan_prioritas" class="form-control" rows="2" placeholder="Berikan alasan singkat..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i>Lengkapi 3 Opsi Judul Berikut:</h6>

                        <div id="judulContainer">
                            </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5" id="btnSimpan">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Pengajuan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-base-body>

</div>
@endsection

@section('scripts')
<script>
    window.AUTH_USER_ID = "{{ auth()->id() }}";
</script>
<script type="module" src="{{ asset('controllers/pengajuan.controller.js') }}"></script>
@endsection


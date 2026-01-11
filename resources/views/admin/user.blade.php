@extends('Layouts.Base')
@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold"><i class="fa-solid fa-book pr-2"></i> Pengguna</h3>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Pengguna</h6>
                <button type="button" class="btn btn-primary mb-3" id="btnTambah">
                    <i class="fa fa-plus"></i> Tambah
                </button>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="penggunaBody">
                        <tr>
                            <td colspan="3" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>



    </div>
    {{-- Modal --}}
    <div class="modal fade" id="DataModalPengguna" tabindex="-1" aria-labelledby="DataModalLabelPengguna"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DataModalLabelPosition">Position</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama">
                            <small id="name-error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="simpanPosition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection

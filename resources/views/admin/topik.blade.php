@extends('Layouts.Base')
@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold"><i class="fa-solid fa-book pr-2"></i> Topik</h3>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
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
                    <tbody id="tBody">
                        <tr>
                            <td colspan="3" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    {{-- Modal --}}
    <div class="modal fade" id="DataModal" tabindex="-1" aria-labelledby="DataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DataModalLabel">Kepakaran Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="topikForm" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id">

                        <!-- DOSEN -->
                        <div class="form-group mb-3">
                            <label for="nama_topik">Nama Topik</label>
                            <input type="text" class="form-control" name="nama_topik" id="nama_topik"
                                placeholder="Masukkan nama_topik">
                            <div class="invalid-feedback" id="nama_topik-error"></div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="simpanData">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            /* =======================
             * GET DATA
             * ======================= */
            function getData() {
                $.ajax({
                    url: "/sitasi/topik",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {

                        let tableBody = "";

                        if (response.data.length === 0) {
                            tableBody = `
                        <tr>
                            <td colspan="3" class="text-center">Data masih kosong</td>
                        </tr>
                    `;
                            $("#tBody").html(tableBody);
                            return;
                        }

                        $.each(response.data, function(index, item) {
                            tableBody += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.nama_topik}</td>
                            <td>
                                <button type="button"
                                    class="btn btn-outline-primary btn-sm edit-btn"
                                    data-id="${item.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm delete-confirm"
                                    data-id="${item.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                        });

                        $("#tBody").html(tableBody);

                        $('#tBody').closest('table').DataTable({
                            destroy: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            order: []
                        });
                    },
                    error: function() {
                        errorAlert('Gagal mengambil data');
                    }
                });
            }

            getData();

            /* =======================
             * CREATE & UPDATE
             * ======================= */
            $(document).on('click', '#simpanData', function(e) {
                e.preventDefault();
                clearErrors();

                let id = $('#id').val();
                let formData = new FormData($('#topikForm')[0]);
                let url = id ? `/sitasi/topik/update/${id}` : '/sitasi/topik/create';

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(response) {
                        Swal.close();

                        if (response.code === 200 || response.status === 'success') {
                            successAlert(response.message ?? 'Data berhasil disimpan');
                            $('#DataModal').modal('hide');

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },

                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.data;

                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '-error').text(value[0]);
                            });
                            return;
                        }

                        errorAlert();
                    }
                });
            });

            /* =======================
             * EDIT
             * ======================= */
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: `/sitasi/topik/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        $('#DataModal').modal('show');
                        $('#DataModalLabel').text('Edit Topik');
                        $('#id').val(response.data.id);
                        $('#nama_topik').val(response.data.nama_topik);
                    },
                    error: function() {
                        errorAlert();
                    }
                });
            });

            /* =======================
             * DELETE
             * ======================= */
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/sitasi/topik/delete/${id}`,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.code === 200 || response.status === 'success') {
                                successAlert('Data berhasil dihapus');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                errorAlert();
                            }
                        },
                        error: function() {
                            errorAlert();
                        }
                    });
                }

                confirmAlert('Apakah yakin ingin menghapus data?', deleteData);
            });

            /* =======================
             * FORM HELPER
             * ======================= */
            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            $(document).on('input', '#topikForm input', function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '-error').text('');
            });

            /* =======================
             * MODAL
             * ======================= */
            $(document).on('click', '#btnTambah', function() {
                $('#topikForm')[0].reset();
                $('#id').val('');
                clearErrors();
                $('#DataModalLabel').text('Tambah Topik');
                $('#DataModal').modal('show');
            });

            $('#DataModal').on('hidden.bs.modal', function() {
                $('#topikForm')[0].reset();
                $('#id').val('');
                clearErrors();
                document.activeElement.blur(); // cegah aria-hidden warning
            });

        });
    </script>
@endsection

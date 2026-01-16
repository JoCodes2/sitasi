@extends('Layouts.Base')
@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-award pr-2"></i> Kepakaran
            </h3>

            <button type="button" class="btn btn-primary btn-sm" id="btnTambah">
                <i class="fa fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dosen</th>
                            <th>Topik</th>
                            <th>Persentase</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                        <tr>
                            <td colspan="6" class="text-center">Memuat data...</td>
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
                    <form id="kepakaranForm" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id">

                        <!-- DOSEN -->
                        <div class="form-group mb-3">
                            <label for="dosen_id">Dosen</label>
                            <select class="form-control" name="dosen_id" id="dosen_id">
                                <option value="">-- Pilih Dosen --</option>
                                {{-- looping dosen --}}
                            </select>
                            <div class="invalid-feedback" id="dosen_id-error"></div>
                        </div>

                        <!-- TOPIK PENELITIAN -->
                        <div class="form-group mb-3">
                            <label for="topik_id">Topik Penelitian</label>
                            <select class="form-control" name="topik_id" id="topik_id">
                                <option value="">-- Pilih Topik --</option>
                                {{-- looping topik --}}
                            </select>
                            <div class="invalid-feedback" id="topik_id-error"></div>
                        </div>

                        <!-- PERSENTASE -->
                        <div class="form-group mb-3">
                            <label for="persentase">Persentase Kepakaran (%)</label>
                            <input type="number" class="form-control" name="persentase" id="persentase" min="0"
                                max="100" placeholder="0 - 100">
                            <div class="invalid-feedback" id="persentase-error"></div>
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

            // Fungsi untuk memuat opsi dosen
            function loadDosen() {
                $.ajax({
                    url: "/sitasi/dosen",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        let options = '<option value="">-- Pilih Dosen --</option>';
                        $.each(response.data, function(index, item) {
                            options +=
                                `<option value="${item.id}">${item.nama_lengkap}</option>`;
                        });
                        $("#dosen_id").html(options);
                    },
                    error: function() {
                        console.log("Gagal mengambil data dosen");
                    }
                });
            }

            // Fungsi untuk memuat opsi topik
            function loadTopik() {
                return $.ajax({
                    url: "/sitasi/topik",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        let options = '<option value="">-- Pilih Topik --</option>';
                        $.each(response.data, function(index, item) {
                            options += `<option value="${item.id}">${item.nama_topik}</option>`;
                        });
                        $("#topik_id").html(options);
                    },
                    error: function() {
                        console.log("Gagal mengambil data topik");
                    }
                });
            }

            // Ambil data user
            function getData() {
                $.ajax({
                    url: "/sitasi/kepakaran",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += `<tr>
                                <td>${index + 1}</td>
                                <td>${item.dosen.nama_lengkap}</td>
                                <td>${item.topik.nama_topik}</td>
                                <td>${item.persentase} %</td>
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
                            </tr>`;
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
                        console.log("Gagal mengambil data dari server");
                    }
                });
            }

            getData();

            // create & update
            $(document).on('click', '#simpanData', function(e) {
                e.preventDefault();
                clearErrors();

                let id = $('#id').val();
                let formData = new FormData($('#kepakaranForm')[0]);
                let url = id ? `/sitasi/kepakaran/update/${id}` : '/sitasi/kepakaran/create';

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(response) {
                        Swal.close();

                        // ✅ SUCCESS SAJA
                        if (response.code === 200 || response.status === 'success') {
                            successAlert(response.message ?? 'Data berhasil disimpan!');
                            $('#DataModal').modal('hide');

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },

                    error: function(xhr) {
                        Swal.close();

                        // ✅ VALIDASI FORM (INI INTINYA)
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.data; // 🔥 PENTING

                            $.each(errors, function(key, value) {
                                let input = $('#' + key);
                                let errorEl = $('#' + key + '-error');

                                input.addClass('is-invalid');
                                errorEl.text(value[0]);
                            });

                            return;
                        }

                        console.error(xhr.responseText);
                        errorAlert();
                    }
                });
            });

            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/sitasi/kepakaran/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let data = response.data;
                        $('#DataModal').modal('show');
                        $('#DataModalLabel').text('Edit Kepakaran');

                        $('#id').val(data.id);
                        $('#persentase').val(data.persentase);

                        // Load options and set selected values
                        $.when(loadDosen(), loadTopik()).done(function() {
                            $('#dosen_id').val(data.dosen_id);
                            $('#topik_id').val(data.topik_id);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data for edit:', error);
                        errorAlert();
                    }
                });
            });

            // Delete data button click handler
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/sitasi/kepakaran/delete/${id}`,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);

                            if (response.code === 200 || response.status === "success") {
                                successAlert('Data berhasil dihapus!');

                                // 🔥 AUTO RELOAD BROWSER
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);

                            } else {
                                errorAlert();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            errorAlert();
                        }
                    });
                }

                confirmAlert('Apakah Anda yakin ingin menghapus data?', deleteData);
            });

            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            $(document).on('input change', '#kepakaranForm input, #kepakaranForm textarea', function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '-error').text('');
            });

            // Tampilkan modal tambah
            $(document).on('click', '#btnTambah', function() {
                $('#kepakaranForm')[0].reset(); // reset form
                $('#id').val('');
                clearErrors();
                $('#DataModalLabel').text('Tambah Kepakaran');
                loadDosen();
                loadTopik();
                $('#DataModal').modal('show');
            });

            // Reset saat modal ditutup
            $('#DataModal').on('hidden.bs.modal', function() {
                $('#kepakaranForm')[0].reset();
                $('#id').val('');
                clearErrors();
            });

        });
    </script>
@endsection

@extends('Layouts.Base')
@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-award pr-2"></i> Pengguna
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Sandi</th>
                            <th>Hak Akses</th>
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
                    <h5 class="modal-title" id="DataModalLabel">Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="userForm" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id">

                        {{-- Nama --}}
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama"
                                placeholder="Masukkan nama">
                            <div class="invalid-feedback" id="nama-error"></div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Masukkan email">
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        {{-- Password --}}
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Masukkan password">
                            <div class="invalid-feedback" id="password-error"></div>
                            {{-- <small class="text-muted">
                                Kosongkan jika tidak ingin mengubah password
                            </small> --}}
                        </div>

                        {{-- Role --}}
                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">-- Pilih Role --</option>
                                <option value="super-admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                            <div class="invalid-feedback" id="role-error"></div>
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

            // Ambil data user
            function getData() {
                $.ajax({
                    url: "/sitasi/user",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += `<tr>
                                <td>${index + 1}</td>
                                <td>${item.nama}</td>
                                <td>${item.email}</td>
                                <td>****</td>
                                <td>${item.role}</td>
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
                let formData = new FormData($('#userForm')[0]);
                let url = id ? `/sitasi/user/update/${id}` : '/sitasi/user/create';

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
                    url: `/sitasi/user/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let data = response.data;
                        $('#DataModal').modal('show');
                        $('#DataModalLabel').text('Edit User');

                        $('#id').val(data.id);
                        $('#nama').val(data.nama);
                        $('#email').val(data.email);
                        $('#password').val(''); // Kosongkan password untuk edit
                        $('#role').val(data.role);
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
                        url: `/sitasi/user/delete/${id}`,
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

            $(document).on('input change', '#userForm input, #userForm textarea', function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '-error').text('');
            });

            // Tampilkan modal tambah
            $(document).on('click', '#btnTambah', function() {
                $('#userForm')[0].reset(); // reset form
                $('#id').val('');
                clearErrors();
                $('#DataModal').modal('show');
            });

            // Reset saat modal ditutup
            $('#DataModal').on('hidden.bs.modal', function() {
                $('#userForm')[0].reset();
                $('#id').val('');
                clearErrors();
            });

        });
    </script>
@endsection

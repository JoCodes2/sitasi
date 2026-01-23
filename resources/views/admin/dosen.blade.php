@extends('Layouts.Base')
@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-chalkboard-user pr-2"></i> Dosen
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
                            <th>Nidn</th>
                            <th>Nama Dosen</th>
                            <th>Gelar</th>
                            <th>Jafung</th>
                            <th>Jabatan Struktural</th>
                            <th>kouta</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">
                        <tr>
                            <td colspan="11" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>



    </div>
    {{-- Modal --}}
    <div class="modal fade" id="DataModal" tabindex="-1" aria-labelledby="DataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DataModalLabel">Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dosenForm" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id">

                        <div class="row">
                            <!-- KIRI -->
                            <div class="col-md-6">

                                <!-- NIDN -->
                                <div class="form-group mb-2">
                                    <label for="nidn">NIDN</label>
                                    <input type="text" class="form-control" name="nidn" id="nidn"
                                        placeholder="Masukkan NIDN">
                                    <div class="invalid-feedback" id="nidn-error"></div>
                                </div>

                                <!-- Nama Lengkap -->
                                <div class="form-group mb-2">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap"
                                        placeholder="Masukkan nama lengkap">
                                    <div class="invalid-feedback" id="nama_lengkap-error"></div>
                                </div>

                                <!-- Gelar -->
                                <div class="form-group mb-2">
                                    <label for="gelar">Gelar</label>
                                    <input type="text" class="form-control" name="gelar" id="gelar"
                                        placeholder="Contoh: S.Kom., M.Kom.">
                                    <div class="invalid-feedback" id="gelar-error"></div>
                                </div>

                                <!-- Jabatan Fungsional -->
                                <div class="form-group mb-2">
                                    <label for="jabatan_fungsional">Jabatan Fungsional</label>
                                    <input type="text" class="form-control" name="jabatan_fungsional"
                                        id="jabatan_fungsional" placeholder="kosongkan jika tidak ada">
                                    <div class="invalid-feedback" id="jabatan_fungsional-error"></div>
                                </div>

                                <!-- Jabatan Struktural -->
                                <div class="form-group mb-2">
                                    <label for="jabatan_struktural">Jabatan Struktural</label>
                                    <input type="text" class="form-control" name="jabatan_struktural"
                                        id="jabatan_struktural" placeholder="kosongkan jika tidak ada">
                                    <div class="invalid-feedback" id="jabatan_struktural-error"></div>
                                </div>

                            </div>

                            <!-- KANAN -->
                            <div class="col-md-6">

                                <!-- Kuota Maksimal -->
                                <div class="form-group mb-2">
                                    <label for="kuota_max">Kuota Maksimal</label>
                                    <input type="number" class="form-control" name="kuota_max" id="kuota_max"
                                        min="1" value="5">
                                    <div class="invalid-feedback" id="kuota_max-error"></div>
                                </div>

                                <!-- Nomor HP -->
                                <div class="form-group mb-2">
                                    <label for="no_hp">Nomor HP</label>
                                    <input type="text" class="form-control" name="no_hp" id="no_hp"
                                        placeholder="Masukkan nomor HP">
                                    <div class="invalid-feedback" id="no_hp-error"></div>
                                </div>

                                <!-- Email -->
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Masukkan email">
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>

                                <!-- Alamat -->
                                <div class="form-group mb-2">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" name="alamat" id="alamat" rows="4" placeholder="Masukkan alamat lengkap"></textarea>
                                    <div class="invalid-feedback" id="alamat-error"></div>
                                </div>

                            </div>
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

            function showX(val) {
                return val && val.trim() !== '' ?
                    val :
                    "<span class='text-danger fw-bold'>x</span>";
            }


            // Ambil data user
            function getData() {
                $.ajax({
                    url: "/sitasi/dosen",
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += "<tr>";
                            tableBody += "<td>" + (index + 1) + "</td>";
                            tableBody += "<td>" + item.nidn + "</td>";
                            tableBody += "<td>" + item.nama_lengkap + "</td>";
                            tableBody += "<td>" + item.gelar + "</td>";
                            tableBody += "<td>" + showX(item.jabatan_fungsional) + "</td>";
                            tableBody += "<td>" + showX(item.jabatan_struktural) + "</td>";

                            tableBody += "<td>" + item.kuota_max + "</td>";
                            tableBody += "<td>" + item.no_hp + "</td>";
                            tableBody += "<td>" + item.email + "</td>";
                            tableBody += "<td>" + item.alamat + "</td>";

                            tableBody += "<td>";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-primary btn-sm edit-btn' data-id='" +
                                item.id + "'><i class='fas fa-edit'></i></button> ";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-danger btn-sm delete-confirm' data-id='" +
                                item.id + "'><i class='fas fa-trash'></i></button>";
                            tableBody += "</td>";
                            tableBody += "</tr>";
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
                let formData = new FormData($('#dosenForm')[0]);
                let url = id ? `/sitasi/dosen/update/${id}` : '/sitasi/dosen/create';

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

            // // Edit data button click handler
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: `/sitasi/dosen/get/${id}`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        $('#DataModal').modal('show');
                        $('#DataModalLabel').text('Edit Dosen');
                        $('#id').val(response.data.id);
                        $('#nidn').val(response.data.nidn);
                        $('#nama_lengkap').val(response.data.nama_lengkap);
                        $('#gelar').val(response.data.gelar);
                        $('#jabatan_fungsional').val(response.data.jabatan_fungsional);
                        $('#jabatan_struktural').val(response.data.jabatan_struktural);
                        $('#kuota_max').val(response.data.kuota_max);
                        $('#no_hp').val(response.data.no_hp);
                        $('#email').val(response.data.email);
                        $('#alamat').val(response.data.alamat);
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
                        url: `/sitasi/dosen/delete/${id}`,
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

            $(document).on('input change', '#dosenForm input, #dosenForm textarea', function() {
                $(this).removeClass('is-invalid');
                $('#' + this.id + '-error').text('');
            });



            // Tampilkan modal tambah
            $(document).on('click', '#btnTambah', function() {
                $('#dosenForm')[0].reset(); // reset form
                $('#id').val('');
                clearErrors();
                $('#DataModalLabel').text('Tambah Dosen');
                $('#DataModal').modal('show');
            });

            // Reset saat modal ditutup
            $('#DataModal').on('hidden.bs.modal', function() {
                $('#dosenForm')[0].reset();
                $('#id').val('');
                clearErrors();
            });

        });
    </script>
@endsection

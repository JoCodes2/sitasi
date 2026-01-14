import gelombangService from "../services/gelombang.service.js";

$(document).ready(function () {
    const gelombang = new gelombangService();
    gelombang.getAllData();

    // Trigger Modal Tambah
    $('#btnTambah').on('click', function () {
        $('#formGelombang')[0].reset();
        $('#gelombang_id').val(''); // Pastikan input hidden ID di form memiliki id="gelombang_id"

        // Bersihkan state validasi
        $('#formGelombang .form-control').removeClass('is-valid is-invalid');
        $('.error-msg').text('');

        $('#modalTambahGelombang').modal('show');
    });

    // Fungsi Validasi
    function validation() {
        $('#formGelombang').validate({
            rules: {
                tahun_ajaran: { required: true },
                semester: { required: true },
                gelombang_ke: { required: true, number: true },
                tgl_mulai: { required: true, date: true },
                tgl_selesai: { required: true, date: true },
            },
            messages: {
                tahun_ajaran: { required: "Tahun ajaran tidak boleh kosong" },
                semester: { required: "Pilih semester" },
                gelombang_ke: { required: "Gelombang tidak boleh kosong", number: "Harus berupa angka" },
                tgl_mulai: { required: "Tanggal mulai harus diisi" },
                tgl_selesai: { required: "Tanggal selesai harus diisi" },
            },
            errorElement: 'small',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                // Masukkan error ke dalam kontainer .error-msg yang sudah kita siapkan di HTML
                const errorId = '#error-' + element.attr('name');
                if ($(errorId).length) {
                    $(errorId).html(error);
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    }

    validation();

    // Validasi real-time saat input berubah
    $('#tahun_ajaran, #semester, #gelombang_ke, #tgl_mulai, #tgl_selesai').on('change input', function () {
        $(this).valid();
    });

    function checkingEdit() {
        return $('#id').val() ? true : false;
    }

    $('#btnSimpanGelombang').on('click', function (e) {
        e.preventDefault();
        if ($('#formGelombang').valid()) {
            gelombang.upsertData($('#formGelombang')[0], checkingEdit);
        }
    });

    $(document).on('click', '.btnEdit', function () {
        const id = $(this).data('id');
        gelombang.getDataById(id);

    });

    $(document).on('click', '.btnHapus', function () {
        const id = $(this).data('id');
        gelombang.deleteData(id);
    });

    $('#modalTambahGelombang').on('hidden.bs.modal', function () {
        $('#formGelombang')[0].reset();
        $('.form-control').removeClass('is-invalid is-valid');
        $('.error-msg').text('');
    });
});

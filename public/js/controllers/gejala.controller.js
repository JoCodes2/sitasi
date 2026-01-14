import gejalaService from "../services/gejala.service.js";


$(document).ready(function () {
    const gejalaservice = new gejalaService();
    gejalaservice.getAllData();

    $('#btnTambahGejala').on('click', function () {
        $('#formGejala')[0].reset();
        $('#id').val('');

        $('#formGejala .form-control').removeClass('is-valid is-invalid');
        $('#kategori-error, #deskripsi_gejala-error, #kode_gejala').text('');

        $('#modalGejala').modal('show');
    });
    function validation() {
        $('#formGejala').validate({
            rules: {
                kode_gejala: { required: true },
                deskripsi_gejala: { required: true },
                kategori: { required: true },
            },
            messages: {
                kode_gejala: { required: "Form tidak boleh kosong" },
                deskripsi_gejala: { required: "Form tidak boleh kosong" },
                kategori: { required: "Form tidak boleh kosong" },
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function (error, element) {
                error.addClass('text-danger text-sm');
                error.insertAfter(element);
            }
        });
    }

    validation();

    $('#kode_gejala, #kategori, #deskripsi_gejala').on('input', function () {
        $(this).valid();
    });


    function checkingEdit() {
        return $('#id').val() ? true : false;
    }
    $('#formGejala').submit(function (e) {
        e.preventDefault();
        gejalaservice.upsertData(e, checkingEdit);
    });


    $(document).on('click', '.edit-gejala', function () {
        const id = $(this).data('id');
        gejalaservice.getDataById(id, checkingEdit);
    });

    $(document).on('click', '.delete-gejala', function () {
        const id = $(this).data('id');
        gejalaservice.deleteData(id);
    });

    $('#modalGejala').on('hidden.bs.modal', function () {
        $('#id').val('');
        $('#nama').val('');
        $('#deskripsi').val('');
        $('.form-control').removeClass('is-invalid').removeClass('is-valid');
        $('.error').remove();
    });
    $('#modalGejala').on('show.bs.modal', function () {
        $('#modal-title').html(`
            <i class="fas fa-box ms-2"></i>
            Form Data
        `);
    });

});

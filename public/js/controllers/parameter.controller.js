import parameterService from "../services/parameter.service.js";


$(document).ready(function () {
    const parameter = new parameterService();
    parameter.getAllData();

    $('#btnTambahParameter').on('click', function () {
        $('#formParameter')[0].reset();
        $('#id').val('');

        $('#formParameter .form-control').removeClass('is-valid is-invalid');
        $('#nama_parameter-error, #satuan-error, #kategori-error, #nilai_ideal_min-error, #nilai_ideal_max-error, #deskripsi-error')
            .text('');

        $('#modalParameter').modal('show');
    });

    function validation() {
        $('#formParameter').validate({
            rules: {
                nama_parameter: { required: true },
                satuan: { required: false },
                kategori: { required: true },
                nilai_ideal_min: { number: true, required: true },
                nilai_ideal_max: {
                    number: true,
                    required: true,
                    greaterThanMin: true
                },
                deskripsi: { required: false }
            },
            messages: {
                nama_parameter: { required: "Nama parameter tidak boleh kosong" },
                kategori: { required: "Kategori wajib dipilih" },
                nilai_ideal_min: { number: "Harus berupa angka", required: 'Form wajib diisi' },
                nilai_ideal_max: {
                    number: "Harus berupa angka",
                    required: 'Form wajib diisi',
                    greaterThanMin: "Nilai maksimal harus lebih besar atau sama dengan nilai minimal"
                }
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

    $.validator.addMethod("greaterThanMin", function (value, element) {
        const min = parseFloat($('#nilai_ideal_min').val());
        const max = parseFloat(value);

        if (!value || !min) return true;

        return max >= min;
    });

    validation();

    $('#nama_parameter, #kategori, #nilai_ideal_min, #nilai_ideal_max').on('input change', function () {
        $(this).valid();
    });

    function checkingEdit() {
        return $('#id').val() ? true : false;
    }

    $('#formParameter').submit(function (e) {
        e.preventDefault();
        parameter.upsertData(e, checkingEdit);
    });


    $(document).on('click', '.edit-parameter', function () {
        const id = $(this).data('id');
        parameter.getDataById(id, checkingEdit);
    });

    $(document).on('click', '.delete-parameter', function () {
        const id = $(this).data('id');
        parameter.deleteData(id);
    });

    $('#modalParameter').on('hidden.bs.modal', function () {
        $('#id').val('');
        $('#formParameter')[0].reset();
        $('#id').val('');

        $('#formParameter .form-control').removeClass('is-valid is-invalid');
        $('#nama_parameter-error, #satuan-error, #kategori-error, #nilai_ideal_min-error, #nilai_ideal_max-error, #deskripsi-error')
            .text('');
        $('.error').remove();
    });
    $('#modalParameter').on('show.bs.modal', function () {
        $('#modal-title').html(`
            <i class="fas fa-box ms-2"></i>
            Form Data
        `);
    });

});

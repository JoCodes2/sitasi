import ajukanJudulService from "../services/pengajuan.service.js";

$(document).ready(function () {
    const pengajuan = new ajukanJudulService();

    pengajuan.loadActiveGelombang();
    pengajuan.bindAjukanButton();
    $.extend($.validator.messages, {
        required: "Field ini wajib diisi.",
        remote: "Harap perbaiki field ini.",
        email: "Silakan masukkan format email yang valid.",
        url: "Silakan masukkan format URL yang valid.",
        date: "Silakan masukkan format tanggal yang valid.",
        number: "Silakan masukkan angka yang valid.",
        digits: "Harap masukkan hanya angka.",
        equalTo: "Harap masukkan nilai yang sama lagi.",
        maxlength: $.validator.format("Maksimal {0} karakter."),
        minlength: $.validator.format("Minimal {0} karakter."),
        rangelength: $.validator.format("Panjang karakter harus antara {0} dan {1}."),
        range: $.validator.format("Harap masukkan nilai antara {0} dan {1}."),
        max: $.validator.format("Harap masukkan nilai kurang dari atau sama dengan {0}."),
        min: $.validator.format("Harap masukkan nilai lebih dari atau sama dengan {0}.")
    });

    const initValidation = function () { // Gunakan window agar bisa diakses dari Service
        const form = $('#formPengajuanJudul');

        form.validate({
            ignore: [],
            rules: {
                gelombang_id: { required: true },
                harapan_judul: { required: true },
                alasan_prioritas: { required: true, minlength: 20 },
                ...Object.assign({}, ...[0, 1, 2].map(i => ({
                    [`details[${i}][judul]`]: { required: true, minlength: 10 },
                    [`details[${i}][latar_belakang]`]: { required: true, minlength: 50 },
                    [`details[${i}][topik_id]`]: { required: true }
                })))
            },
            messages: {
                // Pesan Custom untuk field tertentu
                gelombang_id: {
                    required: "Periode gelombang tidak terdeteksi, silakan refresh halaman."
                },
                harapan_judul: {
                    required: "Pilihan prioritas judul wajib ditentukan."
                },
                alasan_prioritas: {
                    required: "Alasan memilih prioritas wajib diisi.",
                    minlength: "Berikan alasan yang lebih jelas (minimal 20 karakter)."
                },
                // Pesan dinamis untuk array details
                ...Object.assign({}, ...[0, 1, 2].map(i => ({
                    [`details[${i}][judul]`]: {
                        required: `Judul ke-${i + 1} wajib diisi.`,
                        minlength: `Judul ke-${i + 1} minimal 10 karakter.`
                    },
                    [`details[${i}][latar_belakang]`]: {
                        required: `Latar belakang ke-${i + 1} wajib diisi.`,
                        minlength: `Latar belakang minimal 50 karakter.`
                    },
                    [`details[${i}][topik_id]`]: {
                        required: `Topik untuk judul ke-${i + 1} harus dipilih.`
                    }
                })))
            },
            errorElement: 'div',
            errorClass: 'text-danger small mt-1',
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function (error, element) {
                // Penempatan error agar rapi (di bawah input/select)
                error.insertAfter(element);
            },
            submitHandler: function (formElement) {
                pengajuan.submitPengajuan(formElement);
            }
        });
    };

    // Inisialisasi awal
    initValidation();
});

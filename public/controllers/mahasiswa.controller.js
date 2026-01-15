import mahasiswaService from "../services/mahasiswa.service.js"

$(document).ready(function () {
    const mahasiswa = new mahasiswaService();
    mahasiswa.getAllData();


    $(document).on('click', '.detailMahasiswa', function () {
        const id = $(this).data('id');
        mahasiswa.getDataById(id);
    });

    $(document).on('input', 'input[name="nim"], input[name="no_hp"], input[name="angkatan"]', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    if ($('#formRegistrasi').length) {
        $('#formRegistrasi').validate({
            rules: {
                nama: { required: true },
                email: { required: true, email: true },
                password: {
                    required: function () { return !$('#mahasiswa_id').val(); },
                    minlength: 8
                },
                password_confirmation: {
                    required: function () { return !$('#mahasiswa_id').val(); },
                    equalTo: "#password"
                },
                nim: { required: true, digits: true, minlength: 8 },
                no_hp: { required: true, digits: true, minlength: 10 },
                prodi: { required: true },
                angkatan: { required: true, digits: true },
                agama: { required: true },
                jenis_kelamin: { required: true },
                tanggal_lahir: { required: true },
                tempat_lahir: { required: true },
                alamat: { required: true }
            },
            messages: {
                nama: { required: "Nama lengkap wajib diisi sesuai ijazah" },
                email: {
                    required: "Alamat email wajib diisi",
                    email: "Format email tidak valid (contoh: mhs@mail.com)"
                },
                tempat_lahir: { required: "Tempat lahir wajib diisi" },
                password: {
                    required: "Password akun wajib diisi",
                    minlength: "Password minimal terdiri dari 8 karakter"
                },
                password_confirmation: {
                    required: "Silakan konfirmasi password Anda",
                    equalTo: "Konfirmasi password tidak cocok dengan password di atas"
                },
                nim: {
                    required: "NIM wajib diisi",
                    digits: "NIM hanya boleh berisi angka",
                    minlength: "NIM minimal 8 digit"
                },
                no_hp: {
                    required: "Nomor HP/WhatsApp aktif wajib diisi",
                    digits: "Nomor HP hanya boleh berisi angka",
                    minlength: "Nomor HP minimal 10 digit"
                },
                prodi: { required: "Silakan pilih Program Studi Anda" },
                angkatan: {
                    required: "Tahun angkatan wajib diisi",
                    digits: "Tahun angkatan harus berupa angka"
                },
                agama: { required: "Silakan pilih agama Anda" },
                jenis_kelamin: { required: "Pilih jenis kelamin" },
                tanggal_lahir: { required: "Tanggal lahir wajib diisi" },
                alamat: { required: "Alamat domisili saat ini wajib diisi" }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');

                if (element.attr("name") == "jenis_kelamin") {
                    error.insertAfter(element.closest('.d-flex'));
                }
                // Jika input menggunakan group (ada icon)
                else if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                }
                else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
        });
    }
    $('#btnDaftar').on('click', function (e) {
        e.preventDefault();
        if ($('#formRegistrasi').valid()) {
            mahasiswa.registrasiMahasiswa($('#formRegistrasi')[0]);
        }
    });
})

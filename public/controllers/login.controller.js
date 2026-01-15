import loginService from "../services/login.service.js";

$(document).ready(function () {
    const authservice = new loginService();

    // 1. Fungsi Toggle Password (Mata)
    $(document).on('click', '.form-password-toggle .cursor-pointer', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $inputGroup = $this.closest('.input-group');
        const $input = $inputGroup.find('input');
        const $icon = $this.find('i');

        if ($input.attr('type') === 'text') {
            $input.attr('type', 'password');
            $icon.removeClass('bx-show').addClass('bx-hide');
        } else {
            $input.attr('type', 'text');
            $icon.removeClass('bx-hide').addClass('bx-show');
        }
    });

    // 2. Fungsi Validasi
    function validation() {
        $('#loginForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                email: {
                    required: "Email tidak boleh kosong",
                    email: "Format email mahasiswa tidak valid"
                },
                password: {
                    required: "Password tidak boleh kosong",
                    minlength: "Password minimal 8 karakter"
                }
            },
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');

                // Jika ada input-group, pesan error diletakkan SETELAH div input-group
                if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                // Tambahkan class is-invalid ke input dan input-group untuk border merah
                $(element).addClass('is-invalid').removeClass('is-valid');
                $(element).closest('.input-group').addClass('invalid-group');
            },
            unhighlight: function (element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
                $(element).closest('.input-group').removeClass('invalid-group');
            },
            submitHandler: function (form, e) {
                e.preventDefault();
                authservice.login(e);
            }
        });
    }

    validation();

    $('input').on('keyup change', function () {
        $(this).valid();
    });
    $('#email, #password').on('input', function () {
        $(this).valid()
    })

    $("#loginForm").submit(function (e) {
        e.preventDefault();
        authservice.login(e)
    })
});

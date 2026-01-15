<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>Login | SITASI STMIK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/assets/stmik.png') }}" />

    @include('Layouts.Styles')

    <style>
        body {
            /* Background Biru Putih Modern */
            background-color: #f5f7ff;
            background-image: radial-gradient(at 0% 0%, rgba(105, 108, 255, 0.15) 0, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(105, 108, 255, 0.1) 0, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 50, 150, 0.1) !important;
            max-width: 420px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .login-header {
            padding: 3rem 2rem 1rem !important;
        }
        .form-label { font-weight: 700; color: #334155; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-group-text { background-color: #fff; border-right: none; }
        .form-control { border-left: none; padding-left: 0; }
        .form-control:focus { border-color: #d9dee3; box-shadow: none; }

        /* Styling Ikon Tambahan */
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f0f2f5;
        }
        .social-icons img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            transition: all 0.3s ease;
        }
        .social-icons img:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: translateY(-3px);
        }
        .btn-primary {
            background-color: #696cff;
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5f61e6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(105, 108, 255, 0.4);
        }
    </style>
    <script>let appUrl = '{{ env('APP_URL') }}';</script>
</head>

<body>
<div class="container-xxl">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="card login-card border-0">
                <div class="card-header login-header text-center bg-transparent border-0">
                    <img src="{{ asset('assets/assets/stmik.png') }}" width="80" alt="Logo" class="mb-3">
                    <h4 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Login</h4>
                    <p class="text-muted small">Sistem Informasi Tata Kelola Judul Skripsi</p>
                </div>

                <div class="card-body px-4 pb-4">
                    <form id="formLogin">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="nim@student.ac.id" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                             <label class="form-label">Password</label>
                            <div class="input-group input-group-merge form-password-toggle">
                                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="········" required>
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary shadow-sm" id="btnLogin">
                                MASUK SEKARANG <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <div class="social-icons">
                        <a href="#" title="Logo 1">
                            <img src="{{ asset('assets/img/20241107_171817.jpg') }}" alt="Instansi 1">
                        </a>
                        <a href="#" title="Adiguna">
                            <img src="{{ asset('assets/img/adiguna.png') }}" alt="Adiguna Logo">
                        </a>
                    </div>

                    <div class="text-center mt-4 pt-1">
                        <p class="mb-0 text-muted small">Belum memiliki akun?
                            <a href="{{ route('registrasi') }}" class="fw-bold text-primary">Daftar Akun Baru</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Layouts.Scripts')

</body>
</html>

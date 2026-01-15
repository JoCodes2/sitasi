<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>Registrasi SITASI | STMIK</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/assets/stmik.png') }}" />

    @include('Layouts.Styles')

    <style>
        body {
            /* Background Biru Putih Modern (Sama dengan Login) */
            background-color: #f5f7ff;
            background-image: radial-gradient(at 0% 0%, rgba(105, 108, 255, 0.15) 0, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(105, 108, 255, 0.1) 0, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 0;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 50, 150, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
        }
        .register-header {
            padding: 2.5rem 2rem 1.5rem !important;
            background: transparent;
            border: none;
        }
        .form-label {
            font-weight: 700;
            color: #334155;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-title {
            border-left: 4px solid #696cff;
            padding-left: 12px;
            margin-bottom: 25px;
            background: rgba(105, 108, 255, 0.05);
            padding-top: 8px;
            padding-bottom: 8px;
            border-radius: 0 8px 8px 0;
        }
        .input-group-text { background-color: #fff; }

        /* Ikon Instansi di Bawah */
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f0f2f5;
        }
        .social-icons img {
            width: 40px;
            height: 40px;
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
            font-weight: 600;
            padding: 12px;
        }
    </style>
    <script>let appUrl = '{{ env('APP_URL') }}';</script>
</head>

<body>
<div class="container-xxl py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card register-card border-0">
                <div class="card-header register-header text-center">
                    <img src="{{ asset('assets/assets/stmik.png') }}" width="70" alt="Logo" class="mb-3">
                    <h4 class="fw-bold mb-1 text-dark">Registrasi Mahasiswa</h4>
                    <p class="text-muted small">Sistem Informasi Tata Kelola Judul Skripsi (SITASI)</p>
                </div>

                <div class="card-body px-4 px-md-5 pb-5">
                    <form id="formRegistrasi">
                        @csrf

                        <div class="section-title">
                            <h6 class="text-primary fw-bold mb-0">STEP 1: AKSES & KEAMANAN</h6>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Input nama lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="xxx@gmail.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Min. 8 Karakter">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-check-shield"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>

                        <div class="section-title">
                            <h6 class="text-primary fw-bold mb-0">STEP 2: BIODATA AKADEMIK</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" id="nim" class="form-control" placeholder="Nomor Induk Mahasiswa">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Program Studi</label>
                                <select name="prodi" id="prodi" class="form-select">
                                    <option value="" selected disabled>Pilih Prodi</option>
                                    <option value="TI">Teknik Informatika</option>
                                    <option value="SI">Sistem Informasi</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Angkatan</label>
                                <input type="number" name="angkatan" id="angkatan" class="form-control" placeholder="2024">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Agama</label>
                                <select name="agama" id="agama" class="form-select">
                                    <option value="" selected disabled>Pilih Agama</option>
                                    <option value="islam">Islam</option>
                                    <option value="kristen">Kristen</option>
                                    <option value="hindu">Hindu</option>
                                    <option value="budha">Budha</option>
                                    <option value="konghucu">Khonghucu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <div class="d-flex mt-2">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="laki">
                                        <label class="form-check-label small fw-bold" for="laki">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="perempuan">
                                        <label class="form-check-label small fw-bold" for="perempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Domisili</label>
                                <textarea name="alamat" class="form-control" id="alamat" rows="2" placeholder="Alamat lengkap saat ini..."></textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3">
                            <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm" id="btnDaftar">
                                <i class="fa-solid fa-user-plus me-2"></i> BUAT AKUN SEKARANG
                            </button>
                        </div>
                    </form>

                    <div class="social-icons">
                        <img src="{{ asset('assets/img/20241107_171817.jpg') }}" alt="Logo 1">
                        <img src="{{ asset('assets/img/adiguna.png') }}" alt="Adiguna">
                    </div>

                    <div class="text-center mt-4">
                        <p class="mb-0 text-muted small">Sudah memiliki akun?
                            <a href="{{ route('login') }}" class="fw-bold text-primary">Masuk di sini</a>
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

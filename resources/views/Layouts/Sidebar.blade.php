<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/assets/stmik.png') }}" alt="Logo" class="img-fluid" width="50" height="50">
            </span>
            <span class="text-start app-brand-text fw-bold ms-2">
                <small>Sitasi</small><br>
                <small>STMIK</small><br>
                <small>Adhi Guna</small>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
       {{-- Cek apakah user yang login memiliki role admin atau super-admin --}}
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super-admin']))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu Utama</span>
        </li>

        <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <i class="menu-icon fa-solid fa-house"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Data Master</span>
        </li>

        <li class="menu-item {{ request()->is('user') ? 'active' : '' }}">
            <a href="/user" class="menu-link">
                <i class="menu-icon fa-solid fa-user-gear"></i>
                <div>Manajemen Pengguna</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('dosen') ? 'active' : '' }}">
            <a href="/dosen" class="menu-link">
                <i class="menu-icon fa-solid fa-chalkboard-user"></i>
                <div>Data Dosen</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('mahasiswa') ? 'active' : '' }}">
            <a href="/mahasiswa" class="menu-link">
                <i class="menu-icon fa-solid fa-user-graduate"></i>
                <div>Data Mahasiswa</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Akademik</span>
        </li>

        <li class="menu-item {{ request()->is('topik') ? 'active' : '' }}">
            <a href="/topik" class="menu-link">
                <i class="menu-icon fa-solid fa-tags"></i>
                <div>Topik Penelitian</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('kepakaran') ? 'active' : '' }}">
            <a href="/kepakaran" class="menu-link">
                <i class="menu-icon fa-solid fa-medal"></i>
                <div>Bidang Kepakaran</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('gelombang') ? 'active' : '' }}">
            <a href="/gelombang" class="menu-link">
                <i class="menu-icon fa-solid fa-calendar-days"></i>
                <div>Gelombang Pengajuan</div>
            </a>
        </li>
        @endif
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Tugas Akhir</span>
        </li>

        <li class="menu-item {{ request()->is('pengajuan*') ? 'active' : '' }}">
            <a href="/pengajuan" class="menu-link">
                <i class="menu-icon fa-solid fa-file-signature"></i>
                <div>Proses Pengajuan</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('judul*') ? 'active' : '' }}">
            <a href="/judul" class="menu-link">
                <i class="menu-icon fa-solid fa-book"></i>
                <div>Daftar Judul</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('finalisasi-ploting*') ? 'active' : '' }}">
            <a href="/finalisasi-ploting" class="menu-link">
                <i class="menu-icon fa-solid fa-file-signature"></i>
                <div>Keputusan Dosen Pembimbing</div>
            </a>
        </li>
        {{-- Cek apakah user yang login memiliki role admin atau super-admin --}}
        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super-admin']))
       <li class="menu-item {{ request()->is('ploting-dosen*') ? 'active' : '' }}">
            <a href="/ploting-dosen" class="menu-link">
                <i class="menu-icon fa-solid fa-chart-line"></i>
                <div>Analisis</div>
            </a>
        </li>
        @endif
    </ul>
</aside>

<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/assets/stmik.png') }}" alt="Logo" class="img-fluid" width="50"
                    height="50">
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
        <!-- ==================== PENGATURAN AKUN ==================== -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Utama</span>
        </li>

        <!-- ==================== DATA MASTER ==================== -->
        <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
            <a href="/" class="menu-link">
                <i class="menu-icon fa-solid fa-user-graduate"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('dosen') ? 'active' : '' }}">
            <a href="/dosen" class="menu-link">
                <i class="menu-icon fa-solid fa-user-graduate"></i>
                <div>Dosen</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('kepakaran') ? 'active' : '' }}">
            <a href="kepakaran" class="menu-link">
                <i class="menu-icon fa-solid fa-award"></i>
                <div>Kepakaran</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('gelombang') ? 'active' : '' }}">
            <a href="/gelombang" class="menu-link">
                <i class="menu-icon fa-solid fa-calendar-check"></i>
                <div>Gelombang Pengajuan</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('mahasiswa') ? 'active' : '' }}">
            <a href="/mahasiswa" class="menu-link">
                <i class="menu-icon fa-solid fa-user-graduate"></i>
                <div>Data Mahasiswa</div>
            </a>
        </li>


    </ul>
</aside>
<!-- / Menu -->

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
            <span class="menu-header-text">Analisis</span>
        </li>


        <li class="menu-item {{ request()->is('/user') ? 'active' : '' }}">
            <a href="/user" class="menu-link">
                <i class="menu-icon fa-solid fa-user-doctor"></i>
                <div>Pengguna</div>
            </a>
        </li>



        <!-- ==================== DATA MASTER ==================== -->
        <li class="menu-item {{ request()->is('/user') ? 'active' : '' }}">
            <a href="/user" class="menu-link">
                <i class="menu-icon fa-solid fa-user-doctor"></i>
                <div>Pengguna</div>
            </a>
        </li>


    </ul>
</aside>
<!-- / Menu -->

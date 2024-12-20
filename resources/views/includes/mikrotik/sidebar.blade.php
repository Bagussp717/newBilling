<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard.index') }}" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/LogoMySemesta.png') }}" width="180" alt="" />
            </a>
            <div class="cursor-pointer close-btn d-xl-none d-block sidebartoggler" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('mikrotik.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard Mikrotik</span>
                    </a>
                </li>

                @role('super-admin|isp|loket')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Profile</span>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('profilemikrotik.index', 'profilemikrotik.edit') ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('profilemikrotik.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="hide-menu">Profile</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp|loket')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Status Pelanggan</span>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs(['secret', 'active', 'nonactive']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('secret') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="hide-menu">Status Pelanggan</span>
                        </a>
                    </li>
                @endrole

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Distribution</span>
                </li>
                <li class="sidebar-item {{ request()->routeIs(['secretMicrotik.index', 'secretMicrotik.create', 'secretMikrotik.edit']) ? 'active selected' : '' }}">
                    <a class="sidebar-link" href="{{ route('secretMicrotik.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Secret Pelanggan</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->

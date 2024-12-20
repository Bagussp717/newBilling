<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard.index') }}" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/LogoMySemesta.png') }}" width="150" alt="mysemesta"/>
            </a>
            <div class="cursor-pointer close-btn d-xl-none d-block sidebartoggler" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>

                @role('super-admin|isp|teknisi|pelanggan')
                    <li
                        class="sidebar-item {{ request()->routeIs(['dashboard.index', 'secretDashboard', 'activeDashboard', 'nonActiveDashboard']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('dashboard.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp|teknisi|pelanggan')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Distribution</span>
                    </li>
                @endrole

                @role('super-admin|isp|teknisi')
                    <li
                        class="sidebar-item {{ request()->routeIs(['cabang.index', 'cabang.create']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('cabang.index') }}" aria-expanded="false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-network">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 20h7" />
                                    <path d="M14 20h7" />
                                    <path d="M10 20a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M12 16v2" />
                                    <path
                                        d="M8 16.004h-1.343c-2.572 -.004 -4.657 -2.011 -4.657 -4.487c0 -2.475 2.085 -4.482 4.657 -4.482c.393 -1.762 1.794 -3.2 3.675 -3.773c1.88 -.572 3.956 -.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486c0 1.927 -1.551 3.487 -3.465 3.487h-2.535" />
                                </svg>
                            </span>
                            <span class="hide-menu">Cabang</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp|teknisi')
                    <li
                        class="sidebar-item {{ request()->routeIs(['odp.index', 'odp.create', 'odp.edit']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('odp.index') }}" aria-expanded="false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-map-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v8" />
                                    <path d="M9 4v13" />
                                    <path d="M15 7v6.5" />
                                    <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M19.001 15.5v1.5" />
                                    <path d="M19.001 21v1.5" />
                                    <path d="M22.032 17.25l-1.299 .75" />
                                    <path d="M17.27 20l-1.3 .75" />
                                    <path d="M15.97 17.25l1.3 .75" />
                                    <path d="M20.733 20l1.3 .75" />
                                </svg>
                            </span>
                            <span class="hide-menu">ODP</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('paket.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-businessplan"></i>
                            </span>
                            <span class="hide-menu">Paket</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp')
                    <li
                        class="sidebar-item {{ request()->routeIs(['loketPembayaran.index', 'search.invoice', 'pembayaran.create']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('loketPembayaran.index') }}" aria-expanded="false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-device-airtag">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 12a8 8 0 1 0 16 0a8 8 0 0 0 -16 0" />
                                    <path d="M9 15v.01" />
                                    <path d="M15 15a6 6 0 0 0 -6 -6" />
                                    <path d="M12 15a3 3 0 0 0 -3 -3" />
                                </svg>
                            </span>
                            <span class="hide-menu">Loket</span>
                        </a>
                    </li>
                @endrole

                @role('loket')
                    <li
                        class="sidebar-item {{ request()->routeIs(['search.invoice', 'pembayaran.create']) ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('loketPembayaran.indexloket') }}" aria-expanded="false">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-device-airtag">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 12a8 8 0 1 0 16 0a8 8 0 0 0 -16 0" />
                                    <path d="M9 15v.01" />
                                    <path d="M15 15a6 6 0 0 0 -6 -6" />
                                    <path d="M12 15a3 3 0 0 0 -3 -3" />
                                </svg>
                            </span>
                            <span class="hide-menu">Loket</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp')
                    <li
                        class="sidebar-item {{ request()->routeIs(['invoice.index', 'invoice.all_invoice', 'invoice.showByPeriod']) ? 'active selected' : '' }}">
                        <a class="sidebar-link dropdown-toggle" href="#" data-bs-toggle="collapse"
                            data-bs-target="#dropdownExamples">
                            <span>
                                <i class="ti ti-clipboard-text"></i>
                            </span>
                            <span class="hide-menu">Invoice</span>
                        </a>
                        <div class="collapse {{ request()->routeIs(['invoice.index', 'invoice.all_invoice', 'invoice.showByPeriod']) ? 'show' : '' }}"
                            id="dropdownExamples">
                            <ul class="list-unstyled ps-3">
                                @role('super-admin|isp|loket')
                                    <li>
                                        <a class="sidebar-link {{ request()->routeIs(['invoice.index', 'invoice.showByPeriod']) ? 'active selected' : '' }}"
                                            href="{{ route('invoice.index') }}">
                                            <span>
                                                <i class="ti ti-devices"></i>
                                            </span>
                                            <span class="hide-menu">Tabel Invoice</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="sidebar-link {{ request()->routeIs('invoice.all_invoice') ? 'active selected' : '' }}"
                                            href="{{ route('invoice.all_invoice') }}">
                                            <span>
                                                <i class="ti ti-file-dollar"></i>
                                            </span>
                                            <span class="hide-menu">Semua Invoice</span>
                                        </a>
                                    </li>
                                @endrole
                            </ul>
                        </div>
                    </li>
                @endrole

                @role('pelanggan')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('invoice.pelanggan') }}">
                            <span>
                                <i class="ti ti-file-dollar"></i>
                            </span>
                            <span class="hide-menu">Invoice Pelanggan</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('pengaduan.create') ? 'active selected' : '' }}">
                        <a class="sidebar-link" href="{{ route('pengaduan.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-file-check"></i>
                            </span>
                            <span class="hide-menu">Pengaduan</span>
                        </a>
                    </li>
                @endrole

                @role('super-admin|isp|teknisi')
                    <li
                        class="sidebar-item {{ request()->routeIs(['tiket.create', 'tiket.edit', 'tiket.index', 'tiket.doneindex']) ? 'active selected' : '' }}">
                        <!-- Sidebar Dropdown -->
                        <a class="sidebar-link dropdown-toggle" href="#" data-bs-toggle="collapse"
                            data-bs-target="#dropdownTiket">
                            <span>
                                <i class="ti ti-ticket"></i> <!-- Ikon Tiket -->
                            </span>
                            <span class="hide-menu">Tiket</span>
                        </a>
                        <!-- Dropdown content -->
                        <div class="collapse {{ request()->routeIs(['tiket.create', 'tiket.edit', 'tiket.index', 'tiket.doneindex']) ? 'show' : '' }}"
                            id="dropdownTiket">
                            <ul class="list-unstyled ps-3">
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['tiket.index', 'tiket.create', 'tiket.edit']) ? 'active selected' : '' }}"
                                        href="{{ route('tiket.index') }}">
                                        <span>
                                            <i class="ti ti-ticket-off"></i>
                                        </span>
                                        <span class="hide-menu">Tiket Terbuka</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['tiket.doneindex']) ? 'active selected' : '' }}"
                                        href="{{ route('tiket.doneindex') }}">
                                        <span>
                                            <i class="ti ti-file-check"></i>
                                        </span>
                                        <span class="hide-menu">Tiket Selesai</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endrole

                <li
                    class="sidebar-item {{ request()->routeIs(['teknisi.index', 'teknisi.create', 'teknisi.edit', 'pelangganTidakAktif.index', 'pelangganTidakAktif.edit', 'isp.index', 'isp.create', 'isp.edit', 'pelanggan.index', 'pelanggan.edit', 'loket.index', 'loket.create', 'loket.edit', 'pelangganTidakAktif.editScreet', 'pelangganTidakAktif.updateScreet']) ? 'active selected' : '' }}">
                    <!-- Sidebar Dropdown -->
                    @role('super-admin|isp')
                        <a class="sidebar-link dropdown-toggle" href="#" data-bs-toggle="collapse"
                            data-bs-target="#dropdownExample">
                            <span>
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="hide-menu">Role</span>
                        </a>
                    @endrole
                    <!-- Dropdown content -->
                    <div class="collapse {{ request()->routeIs(['teknisi.index', 'teknisi.create', 'teknisi.edit', 'isp.index', 'isp.create', 'isp.edit', 'pelanggan.index', 'pelangganTidakAktif.index', 'pelangganTidakAktif.edit', 'pelanggan.create', 'pelanggan.edit', 'loket.index', 'loket.create', 'loket.edit', 'pelangganTidakAktif.editScreet', 'pelangganTidakAktif.updateScreet']) ? 'show' : '' }}"
                        id="dropdownExample">
                        <ul class="list-unstyled ps-3">
                            @role('super-admin|isp')
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['teknisi.index', 'teknisi.create', 'teknisi.edit']) ? 'active selected' : '' }}"
                                        href="{{ route('teknisi.index') }}">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-cog">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2.5" />
                                                <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M19.001 15.5v1.5" />
                                                <path d="M19.001 21v1.5" />
                                                <path d="M22.032 17.25l-1.299 .75" />
                                                <path d="M17.27 20l-1.3 .75" />
                                                <path d="M15.97 17.25l1.3 .75" />
                                                <path d="M20.733 20l1.3 .75" />
                                            </svg>
                                        </span>
                                        <span class="hide-menu">Teknisi</span>
                                    </a>
                                </li>
                            @endrole
                            @role('super-admin')
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['isp.index', 'isp.create', 'isp.edit']) ? 'active selected' : '' }}"
                                        href="{{ route('isp.index') }}">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-shield">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2" />
                                                <path
                                                    d="M22 16c0 4 -2.5 6 -3.5 6s-3.5 -2 -3.5 -6c1 0 2.5 -.5 3.5 -1.5c1 1 2.5 1.5 3.5 1.5z" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            </svg>
                                        </span>
                                        <span class="hide-menu">ISP</span>
                                    </a>
                                </li>
                            @endrole
                            @role('super-admin|isp')
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['loket.index', 'loket.create', 'loket.edit']) ? 'active selected' : '' }}"
                                        href="{{ route('loket.index') }}">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-dollar">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h3" />
                                                <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                                <path d="M19 21v1m0 -8v1" />
                                            </svg>
                                        </span>
                                        <span class="hide-menu">Loket</span>
                                    </a>
                                </li>
                            @endrole
                            @role('super-admin|isp')
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['pelanggan.index', 'pelanggan.edit']) ? 'active selected' : '' }}"
                                        href="{{ route('pelanggan.index') }}">
                                        <span>
                                            <i class="ti ti-user-check fs-6"></i>
                                        </span>
                                        <span class="hide-menu">Pelanggan Aktif</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidebar-link {{ request()->routeIs(['pelangganTidakAktif.index', 'pelangganTidakAktif.edit', 'pelangganTidakAktif.editScreet', 'pelangganTidakAktif.updateScreet']) ? 'active selected' : '' }}"
                                        href="{{ route('pelangganTidakAktif.index') }}">
                                        <span>
                                            <i class="ti ti-user-x fs-6"></i>
                                        </span>
                                        <span class="hide-menu">Pelanggan Tidak Aktif</span>
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </div>
                </li>

                @role('teknisi')
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->routeIs(['pelanggan.index', 'pelanggan.edit']) ? 'active selected' : '' }}"
                            href="{{ route('pelanggan.index') }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                    <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                    <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                </svg>
                            </span>
                            <span class="hide-menu">Pelanggan</span>
                        </a>
                    </li>
                @endrole

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->

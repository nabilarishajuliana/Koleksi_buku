<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- MASTER DATA (submenu) --}}
        <li class="nav-item {{ request()->routeIs('kategori.*', 'buku.*', 'barang.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#masterMenu"
                aria-expanded="{{ request()->routeIs('kategori.*', 'buku.*', 'barang.*') ? 'true' : 'false' }}"
                aria-controls="masterMenu">
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-database menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('kategori.*', 'buku.*', 'barang.*') ? 'show' : '' }}" id="masterMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                            href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
                            href="{{ route('buku.index') }}">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.index', 'barang.create', 'barang.edit') ? 'active' : '' }}"
                            href="{{ route('barang.index') }}">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.cetak.index') ? 'active' : '' }}"
                            href="{{ route('barang.cetak.index') }}">Tag Harga</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- MODUL JS & JQUERY (submenu) --}}
        <li class="nav-item {{ request()->routeIs('js.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#jsMenu"
                aria-expanded="{{ request()->routeIs('js.*') ? 'true' : 'false' }}"
                aria-controls="jsMenu">
                <span class="menu-title">Modul JS & jQuery</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-language-javascript menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('js.*') ? 'show' : '' }}" id="jsMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('js.tabel_biasa') ? 'active' : '' }}"
                            href="{{ route('js.tabel_biasa') }}">SC2 - Tabel Biasa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('js.tabel_datatables') ? 'active' : '' }}"
                            href="{{ route('js.tabel_datatables') }}">SC2 - DataTables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('js.sc4_select') ? 'active' : '' }}"
                            href="{{ route('js.sc4_select') }}">SC4 - Select & Select2</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- MODUL AJAX JQUERY & AXIOS (submenu) --}}
        <li class="nav-item {{ request()->routeIs('ajax.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ajaxMenu"
                aria-expanded="{{ request()->routeIs('ajax.*') ? 'true' : 'false' }}"
                aria-controls="ajaxMenu">
                <span class="menu-title">Modul AJAX & Axios</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-transfer menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('ajax.*') ? 'show' : '' }}" id="ajaxMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ajax.wilayah') ? 'active' : '' }}"
                            href="{{ route('ajax.wilayah') }}">AJAX AXIOS - Wilayah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ajax.pos') ? 'active' : '' }}"
                            href="{{ route('ajax.pos') }}">Ajax jQuery - POS Kasir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ajax.pos.axios') ? 'active' : '' }}"
                            href="{{ route('ajax.pos.axios') }}">Axios - POS Kasir</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- CUSTOMER (submenu) --}}
        <li class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#customerMenu"
                aria-expanded="{{ request()->routeIs('customer.*') ? 'true' : 'false' }}"
                aria-controls="customerMenu">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('customer.*') ? 'show' : '' }}" id="customerMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}"
                            href="{{ route('customer.index') }}">Data Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.tambah1') ? 'active' : '' }}"
                            href="{{ route('customer.tambah1') }}">Tambah Customer 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.tambah2') ? 'active' : '' }}"
                            href="{{ route('customer.tambah2') }}">Tambah Customer 2</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>
</nav>
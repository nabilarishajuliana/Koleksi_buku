<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-view-list menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>
        <!-- <li class="nav-item {{ request()->is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Tag Harga</span>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
            </a>
        </li> -->
        {{-- Data Barang --}}
        <li class="nav-item {{ request()->routeIs('barang.index', 'barang.create', 'barang.edit') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Data Barang</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
        </li>

        {{-- Tag Harga --}}
        <li class="nav-item {{ request()->routeIs('barang.cetak.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.cetak.index') }}">
                <span class="menu-title">Tag Harga</span>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('js.tabel_biasa') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_biasa') }}">
                <span class="menu-title">SC2 - Tabel Biasa</span>
                <i class="mdi mdi-table menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('js.tabel_datatables') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_datatables') }}">
                <span class="menu-title">SC2 - DataTables</span>
                <i class="mdi mdi-table-large menu-icon"></i>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('js.sc4_select') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.sc4_select') }}">
                <span class="menu-title">SC4 - Select & Select2</span>
                <i class="mdi mdi-form-select menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
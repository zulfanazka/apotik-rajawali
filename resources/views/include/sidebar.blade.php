@php
    $currentRoute = Route::currentRouteName();
    $user = Auth::user();
@endphp
<style>
    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .nav-link.active {
        font-weight: bold;
        color: #fff;
    }
    
</style>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">{{ ucfirst($user->role) }}</div>
                <a class="nav-link {{ $currentRoute == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link collapsed {{ in_array($currentRoute, ['stokbarang', 'barangmasuk', 'barangkeluar']) ? '' : 'collapsed' }}"
                    href="#" data-toggle="collapse" data-target="#collapseLayouts"
                    aria-expanded="{{ in_array($currentRoute, ['stokbarang', 'barangmasuk', 'barangkeluar']) ? 'true' : 'false' }}"
                    aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Inventory
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse {{ in_array($currentRoute, ['stokbarang', 'barangmasuk', 'barangkeluar']) ? 'show' : '' }}"
                    id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ $currentRoute == 'stokbarang' ? 'active' : '' }}"
                            href="{{ route('stokbarang') }}">Stok barang</a>
                        <a class="nav-link {{ $currentRoute == 'barangmasuk' ? 'active' : '' }}"
                            href="{{ route('barangmasuk') }}">Barang masuk</a>
                        <a class="nav-link {{ $currentRoute == 'barangkeluar' ? 'active' : '' }}"
                            href="{{ route('barangkeluar') }}">Barang keluar</a>
                    </nav>
                </div>

                <a class="nav-link {{ $currentRoute == 'laporan' ? 'active' : '' }}" href="{{ route('laporan') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Laporan
                </a>
            </div>
        </div>
    </nav>
</div>

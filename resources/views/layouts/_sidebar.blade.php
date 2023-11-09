<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <h3>{{ Auth::user()->workshop->name }}</h3>
    </div>
    <ul class="c-sidebar-nav">
        @if(Auth::user()->role == 'manager')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('') }}">
                <i class="c-sidebar-nav-icon fas fa-tachometer-alt fa-fw"></i> Dashboard
            </a>
        </li>
        <li class="c-sidebar-nav-title">Manage</li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('workshop') }}">
                <i class="c-sidebar-nav-icon fas fa-warehouse fa-fw"></i> Bengkel
            </a>
        </li>
        <li class="c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon fas fa-boxes fa-fw"></i> Suku Cadang
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('sparepart') }}">
                        <i class="c-sidebar-nav-icon fas fa-list fa-fw"></i> Daftar Suku Cadang
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('report') }}">
                        <i class="c-sidebar-nav-icon fas fa-file-download fa-fw"></i> Laporan Stok Rendah
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('wholesale') }}">
                        <i class="c-sidebar-nav-icon fas fa-cart-plus fa-fw"></i> Pembelian Suku Cadang
                    </a>
                </li>
            </ul>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('service') }}">
                <i class="c-sidebar-nav-icon fas fa-tools fa-fw"></i> Jasa
            </a>
        </li>
        <li class="c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon fas fa-user-ninja fa-fw"></i> Mekanik
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('mechanic') }}">
                        <i class="c-sidebar-nav-icon fas fa-list fa-fw"></i> Daftar Mekanik
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('salary') }}">
                        <i class="c-sidebar-nav-icon fas fa-file-invoice-dollar fa-fw"></i> Ambil Gaji
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('loan') }}">
                        <i class="c-sidebar-nav-icon fas fa-hand-holding-usd fa-fw"></i> Pinjaman
                    </a>
                </li>
            </ul>
        </li>
        @elseif(Auth::user()->role == 'cashier')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('transaction') }}">
                <i class="c-sidebar-nav-icon fas fa-shopping-cart fa-fw"></i> Transaksi
            </a>
        </li>
        <li class="c-sidebar-nav-title">Manage</li>
        <li class="c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon fas fa-boxes fa-fw"></i> Suku Cadang
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('sparepart') }}">
                        <i class="c-sidebar-nav-icon fas fa-list fa-fw"></i> Daftar Suku Cadang
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{ url('report') }}">
                        <i class="c-sidebar-nav-icon fas fa-file-download fa-fw"></i> Laporan Stok Rendah
                    </a>
                </li>
            </ul>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('service') }}">
                <i class="c-sidebar-nav-icon fas fa-tools fa-fw"></i> Jasa
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('mechanic') }}">
                <i class="c-sidebar-nav-icon fas fa-user-ninja fa-fw"></i> Mekanik
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('customer') }}">
                <i class="c-sidebar-nav-icon fas fa-user-tie fa-fw"></i> Pelanggan
            </a>
        </li>
        @elseif(Auth::user()->role == 'mechanic')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('') }}">
                <i class="c-sidebar-nav-icon fas fa-tachometer-alt fa-fw"></i> Dashboard
            </a>
        </li>
        <li class="c-sidebar-nav-title">Manage</li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('salary') }}">
                <i class="c-sidebar-nav-icon fas fa-file-invoice-dollar fa-fw"></i> Gaji
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{ url('loan') }}">
                <i class="c-sidebar-nav-icon fas fa-hand-holding-usd fa-fw"></i> Pinjaman
            </a>
        </li>
        @endif
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>

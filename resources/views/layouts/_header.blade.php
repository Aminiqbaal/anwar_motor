<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <svg class="c-icon c-icon-lg">
            <use xlink:href="{{ url('vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
        </svg>
    </button>
    <a class="c-header-brand d-lg-none" href="#">
        <svg width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ url('assets/brand/coreui.svg#full') }}"></use>
        </svg>
    </a>
    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
        <svg class="c-icon c-icon-lg">
            <use xlink:href="{{ url('vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
        </svg>
    </button>
    <ul class="c-header-nav ml-auto mr-4">
        @auth
        @if (Auth::user()->role != 'cashier')
        <li class="c-header-nav-item dropdown d-md-down-none mx-2">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <svg class="c-icon">
                    <use xlink:href="{{ url('vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                </svg>
                <span class="badge badge-pill badge-danger" id="notification_count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0">
                <div class="dropdown-header bg-light">Pemberitahuan</div>
                <div id="notification_content">

                </div>
            </div>
        </li>
        @endif
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->username }} <i class="fas fa-caret-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @if (Auth::user()->role == 'mechanic')
                <a class="dropdown-item" href="{{ url('user/' . Auth::id()) }}">
                    <svg class="c-icon mr-2">
                        <use xlink:href="{{ url('vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                    </svg> Profile
                </a>
                <div class="dropdown-divider"></div>
                @endif
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="c-icon mr-2">
                        <use xlink:href="{{ url('vendors/@coreui/icons/svg/free.svg#cil-account-logout') }}"></use>
                    </svg> Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
        @else
        <a href="{{ route('login') }}">Login</a>
        @endauth
    </ul>
</header>

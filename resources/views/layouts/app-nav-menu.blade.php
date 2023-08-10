<nav class="bottom-navbar border-bottom">
    <div class="container">
        <ul class="nav page-navigation">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="icon-grid menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            @if(Auth::user()->user_type == 'admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('master_products') }}">
                        <i class="icon-social-soundcloud menu-icon"></i>
                        <span class="menu-title">Master Product</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('subscription_terms') }}">
                        <i class="icon-social-soundcloud menu-icon"></i>
                        <span class="menu-title">Subscription Terms</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="icon-user menu-icon"></i>
                        <span class="menu-title">Users</span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('create_safelink') }}">
                        <i class="icon-social-soundcloud menu-icon"></i>
                        <span class="menu-title">Create New Safelink</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('billings') }}">
                        <i class="icon-social-soundcloud menu-icon"></i>
                        <span class="menu-title">My Billings</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://support.safe-link.net/">
                        <i class="icon-user menu-icon"></i>
                        <span class="menu-title">Support Tickets</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://www.tokopedia.com/koperasiuub?source=universe&st=product">
                        <i class="icon-user menu-icon"></i>
                        <span class="menu-title">Buy Devices</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>

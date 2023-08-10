<div class="progress wd" id="appprogress"></div>
<nav class="navbar top-navbar col-lg-12 col-12 p-0">

    <div class="container">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start me-lg-3">
            <div>
                <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                    <img src="{{asset('/images/WHITE/FA-Logo-SafeLink_White-Horizontal-400x200.png?ver='.time()) }}"
                         alt="logo"/>
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
                    <img src="{{asset('/images/WHITE/FA-Logo-SafeLink_White-Horizontal-400x200.png?ver='.time()) }}"
                         alt="logo"/>
                </a>
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav navbar-nav-right">
                @if(Auth::user()->user_type == 'user')
                    <?php
                    $spaces = \App\Models\Space::where('user_id', Auth::id())->get();
                    ?>
                    <li class="nav-item dropdown  d-none d-lg-flex">
                        <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split"
                           id="selectSpace" data-select-index="" href="#" data-bs-toggle="dropdown" aria-expanded="false"> Select
                            Space </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                             aria-labelledby="selectDropdown">
                            <a class="dropdown-item py-3">
                                <p class="mb-0 font-weight-medium float-left">Select Space</p>
                            </a>
                            <div class="dropdown-divider"></div>

                            @foreach($spaces as $key => $space)
                                <a class="dropdown-item preview-item pt-1 pb-1 border-bottom space-changer" onclick="$('.space-changer').trigger('onchange');" data-space="{{ $space->id }}" data-space-name="{{ $space->name }}" data-space-index="{{ ($key+1) }}">
                                    <div class="preview-item-content flex-grow pt-1 pb-1">
                                        <p class="preview-subject ellipsis font-weight-medium text-white mb-1 ">{{ $space->name }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endif
                <li class="nav-item  d-none d-lg-flex">
                    <form class="search-form" action="#">
                        <i class="icon-search"></i>
                        <input type="search" class="form-control" placeholder="Search Here" title="Search here">
                    </form>
                </li>

                <li class="nav-item dropdown  d-none d-lg-flex user-dropdown">
                    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <img class="img-xs rounded-circle" src="./images/faces/face8.jpg" alt="Profile image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                         aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">
                            <img class="img-md rounded-circle" src="./images/faces/face8.jpg"
                                 alt="Profile image">
                            <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name }}</p>
                            <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p>
                        </div>
                        {{--<a class="dropdown-item"><i
                                    class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My
                            Profile <span class="badge badge-pill badge-danger">1</span></a>
--}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i
                                    class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>{{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="horizontal-menu-toggle" title="button">
                <span class="ti-menu"></span>
            </button>
        </div>
    </div>
</nav>


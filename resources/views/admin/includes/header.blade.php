<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}" alt="" height="40" width="50">
                    </span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}" alt="" height="40" width="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="{{ trans('translation.search')}}...">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form>

          
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{ trans('translation.search')}} ..." aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(App::getLocale() == "ar")
                       <img id="header-lang-img" src="{{ asset(ASSET_PATH.'assets/backend/images/flags/saudia.png')}}" alt="Header Language" height="16">
                    @else
                       <img id="header-lang-img" src="{{ asset(ASSET_PATH.'assets/backend/images/flags/us.jpg')}}" alt="Header Language" height="16">
                    @endif
                    </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->

                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                  
                        <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item notify-item language" >
                            <span class="align-middle"> {{ $properties['native'] }} </span>
                        </a>
                  
                    @endforeach
                   
                </div>
            </div>

       

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   
                    <img class="rounded-circle header-profile-user" @if(!empty(auth()->guard('admin')->user()->photo)) src="{{asset(ASSET_PATH.'public/uploads/admins/'. auth()->guard('admin')->user()->photo)}}" @else   src="{{asset('assets/backend/images/profile-img.png')}}"  @endif 
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ auth()->guard('admin')->user()->user_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('admin.edit_profile' , auth()->guard('admin')->user()->id) }}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                    <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                </div>
            </div>

        </div>
    </div>
</header>
 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">{{trans('translation.menu')}} </li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards"> {{trans('translation.Dashboard')}} </span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-area"></i>
                        <span key="t-dashboards">{{ trans('translation.onboardings')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('onboardings.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                        <li><a href="{{ route('onboardings.create') }}" key="t-default">{{ trans('translation.create')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-customize"></i>
                        <span key="t-dashboards">{{ trans('translation.categories')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('categories.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                        <li><a href="{{ route('categories.create') }}" key="t-default">{{ trans('translation.create')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt"></i>
                        <span key="t-dashboards">{{ trans('translation.subcategories')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('subcategories.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                        <li><a href="{{ route('subcategories.create') }}" key="t-default">{{ trans('translation.create')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-shape-circle"></i>
                        <span key="t-dashboards">{{ trans('translation.brands')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('brands.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                        <li><a href="{{ route('brands.create') }}" key="t-default">{{ trans('translation.create')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-timer"></i>
                        <span key="t-dashboards">{{ trans('translation.mazdats')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('mazdats.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bxl-product-hunt"></i>
                        <span key="t-dashboards">{{ trans('translation.products')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('products.index') }}" key="t-default">{{ trans('translation.all')}}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-hive"></i>
                        <span key="t-dashboards">{{ trans('translation.attributes')}}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('attributes.create')}}" key="t-default">{{ trans('translation.category_attributes')}}</a></li>
                    </ul>
                </li>


                <li>
                    <a href="{{ route('usage_policies.index') }}" class="">
                        <i class="bx bx-shield-quarter"></i>
                        <span key="t-dashboards">{{ trans('translation.usage_policies')}}</span>
                    </a>
    
                </li>

                <li>
                    <a href="{{ route('privacy_policies.index') }}" class="">
                        <i class="bx bx-lock-alt"></i>
                        <span key="t-dashboards">{{ trans('translation.privacy_policies')}}</span>
                    </a>
    
                </li>

                <li>
                    <a href="{{ route('abouts.index') }}" class="">
                        <i class="bx bx-phone-incoming"></i>
                        <span key="t-dashboards">{{ trans('translation.about')}}</span>
                    </a>
    
                </li>

                <li>
                    <a href="{{ route('mazad_user.bills') }}" class="waves-effect">
                        <i class="bx bx-money"></i>
                        <span key="t-dashboards">{{ trans('translation.mazdat_bills') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('product_user.bills') }}" class="waves-effect">
                        <i class="bx bx-money"></i>
                        <span key="t-dashboards">{{ trans('translation.product_bills') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('ads.index') }}" class="waves-effect">
                        <i class="bx bx-slideshow"></i>
                        <span key="t-dashboards">{{ trans('translation.ads') }}</span>
                    </a>
                </li>

                
                <li>
                    <a href="{{ route('users.index') }}" class="waves-effect">
                        <i class="bx bx-user"></i>
                        <span key="t-dashboards">{{ trans('translation.users') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admins.index') }}" class="waves-effect">
                        <i class="bx bx-user"></i>
                        <span key="t-dashboards">{{ trans('translation.Admins') }}</span>
                    </a>
                </li>
                
            
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

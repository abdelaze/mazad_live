<!doctype html>
<html lang="en" @if(App::getLocale() == "ar")  dir="rtl" @else  dir="ltr" @endif>

    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}">
        @include('admin.includes.head_css')
    </head>
    <body data-sidebar="dark">
        
        <!-- <body data-layout="horizontal" data-topbar="dark"> -->

            <!-- Begin page -->
            <div id="layout-wrapper">
                @include('admin.includes.header')
                @include('admin.includes.sidebar')
                <!-- ============================================================== -->
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                     <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                @include('admin.includes.footer')
               </div>
            <!-- end main content-->
            </div>
            <!-- END layout-wrapper -->
            <!-- Right Sidebar -->
            @include('admin.includes.right-sidebar')
            <!-- /Right-bar -->
            <!-- JAVASCRIPT -->
            @include('admin.includes.vendor-scripts')
     </body>

</html>



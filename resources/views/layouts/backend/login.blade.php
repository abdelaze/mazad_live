<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Login | Mazdat </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset(ASSET_PATH.'assets/backend/images/logo.jpeg')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset(ASSET_PATH.'assets/backend/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset(ASSET_PATH.'assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset(ASSET_PATH.'assets/backend/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    @yield('content')
                </div>
            </div>
        </div>
         <!-- JAVASCRIPT -->
         <script src="{{asset(ASSET_PATH.'assets/backend/libs/jquery/jquery.min.js')}}"></script>
         <script src="{{asset(ASSET_PATH.'assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
         <script src="{{asset(ASSET_PATH.'assets/backend/libs/metismenu/metisMenu.min.js')}}"></script>
         <script src="{{asset(ASSET_PATH.'assets/backend/libs/simplebar/simplebar.min.js')}}"></script>
         <script src="{{asset(ASSET_PATH.'assets/backend/libs/node-waves/waves.min.js')}}"></script>
         <!-- App js -->
         <script src="{{asset(ASSET_PATH.'assets/backend/js/app.js')}}"></script>
    </body>
 </html>
    

        
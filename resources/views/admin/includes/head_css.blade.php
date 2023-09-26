 <!-- Bootstrap Css -->
 @if(App::getLocale() == "ar") 
 <link href="{{asset(ASSET_PATH.'assets/backend/css/bootstrap-rtl.min.css')}}"  rel="stylesheet" type="text/css" />
 @else
 <link href="{{asset(ASSET_PATH.'assets/backend/css/bootstrap.min.css')}}"  rel="stylesheet" type="text/css" />
 @endif 
 <!-- Icons Css -->
 <link href="{{asset(ASSET_PATH.'assets/backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

 <!-- App Css-->
 @stack('css'); 
 
 @if(App::getLocale() == "ar")
   <link href="{{asset(ASSET_PATH.'assets/backend/css/app-rtl.min.css')}}"  rel="stylesheet" type="text/css" />
@else
<link href="{{asset(ASSET_PATH.'assets/backend/css/app.min.css')}}"  rel="stylesheet" type="text/css" />
@endif

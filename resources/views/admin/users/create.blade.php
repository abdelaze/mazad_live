@extends('layouts.backend.master')
@section('title') @lang('translation.add_user') @endsection
@push('css')
    <!-- select2 css -->
    <link href="{{asset(ASSET_PATH.'assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- dropzone css -->
    <link href="{{ asset(ASSET_PATH.'assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />    
@endpush
@section('content')
   
      

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                       

                        <form action="{{route('users.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="user_name"> {{ trans('translation.user_name') }} </label>
                                        <input id="user_name" name="user_name" type="text" class="form-control" placeholder="User Name" value="{{ old('user_name') }}" required>
                                        @error('user_name')
                                            <span style="color: red"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password"> {{ trans('translation.password') }} </label>
                                        <input id="password" name="password" type="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required>
                                        @error('password')
                                            <span style="color: red"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password"> {{ trans('translation.confirm_password') }} </label>
                                        <input id="password" name="confirm-password" type="password" class="form-control" placeholder="Password" value="{{ old('password') }}" required>
                                        @error('confirm-password')
                                            <span style="color: red"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">

                                    <div class="mb-3">
                                        <label for="email">{{ trans('translation.email') }} </label>
                                        <input id="email" name="email" type="email" class="form-control" placeholder="User Email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span style="color: red"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                  
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">{{ trans('translation.save') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

@endsection
@push('script')
     <!-- Ion Range Slider-->
     <script src="{{asset(ASSET_PATH.'assets/libs/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
     <!-- init js -->
     <script src="{{asset(ASSET_PATH.'assets/js/pages/product-filter-range.init.js')}}"></script>
@endpush
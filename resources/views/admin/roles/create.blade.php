@extends('layouts.backend.master')
@section('title') @lang('translation.add_role') @endsection
@push('css')
    <!-- select2 css -->
    <link href="{{asset(ASSET_PATH.'assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- dropzone css -->
    <link href="{{ asset(ASSET_PATH.'assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />    
@endpush
@section('content')
   
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Add Role</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Barber</a></li>
                            <li class="breadcrumb-item active">Add Role</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Basic Information</h4>
                        <p class="card-title-desc">Fill all information below</p>

                        <form action="{{route('roles.store')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="name"> Name</label>
                                        <input id="name" name="name" type="text" class="form-control" placeholder="Role Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span style="color: red"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <h5 class="font-size-14 mb-4"> Permission
                                        </h5>
                                        <div class="row">
                                        @foreach($permission as $value)
                                        <div class="col-sm-6">    
                                        <div class="form-check mb-3">
                                         <input class="form-check-input" type="checkbox" value="{{$value->id}}"  name="permission[]" id="permission">
                                         <label class="form-check-label" for="permission">
                                           {{ $value->name }}
                                         </label>
                                         </div>
                                         </div>
                                        @endforeach
                                        @error('permission')
                                        <span style="color: red"> {{ $message }}</span>
                                    @enderror
                                        </div>
                                    </div>    
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
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
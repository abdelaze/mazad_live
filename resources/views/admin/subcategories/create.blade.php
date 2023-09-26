@extends('layouts.backend.master')
@section('title') {{ trans('translation.subcategories') }} @endsection
@push('css')
    <!-- select2 css -->
    <link href="{{asset(ASSET_PATH.'assets/backend/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- dropzone css -->
    <link href="{{ asset(ASSET_PATH.'assets/backend/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />    
@endpush

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ trans('translation.add_subcategory') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('translation.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('translation.subcategories') }}</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ trans('translation.create_new_subcategory') }}</h4>
                        <form  action="{{route('subcategories.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div data-repeater-list="outer-group" class="outer">
                                <div data-repeater-item class="outer">
                                    <div class="form-group row mb-4">
                                        <label for="name" class="col-form-label col-lg-2">{{ trans('translation.name_en') }}</label>
                                        <div class="col-lg-10">
                                            <input id="name" name="name" type="text" class="form-control" placeholder="{{ trans('translation.name_en') }}" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>   
   
                                    <div class="form-group row mb-4">
                                        <label for="name_ar" class="col-form-label col-lg-2">{{ trans('translation.name_ar') }}</label>
                                        <div class="col-lg-10">
                                            <input id="name_ar" name="name_ar" type="text" class="form-control" placeholder="{{ trans('translation.name_ar') }}" value="{{ old('name_ar') }}" required>
                                            @error('name_ar')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                   
                                    <div class="form-group row mb-4">
                                        <label for="category_id" class="col-form-label col-lg-2">{{ trans('translation.category') }}</label>
                                        <div class="col-lg-10">
                                            <select class="form-control select2" name="category_id" required>
                                                <option>{{ trans('translation.select_category') }}</option>
                                                @foreach ($cats as $cat)
                                                <option value="{{ $cat->id }}"> {{ $cat->name }} </option> 
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('category_id')
                                        <span style="color: red"> {{ $message }}</span>
                                         @enderror
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-lg-2">{{ trans('translation.status') }}</label>
                                        <div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckSizelg" name="status" checked>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-lg-10">
                                    <button type="submit" class="btn btn-primary">{{ trans('translation.save') }}</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

@endsection

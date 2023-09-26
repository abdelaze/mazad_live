@extends('layouts.backend.master')
@section('title') {{  trans('translation.edit_category') }} @endsection

@section('content')
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{  trans('translation.edit_category') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{  trans('translation.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{  trans('translation.edit_category') }}</li>
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
                        <h4 class="card-title mb-4">{{  trans('translation.edit_category') }}</h4>
                        <form action="{{route('categories.update' , $category->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div data-repeater-list="outer-group" class="outer">
                                <div data-repeater-item class="outer">
                                   
                                    <div class="form-group row mb-4">
                                        <label for="name" class="col-form-label col-lg-2">{{ trans('translation.name_en') }}</label>
                                        <div class="col-lg-10">
                                            <input id="name" name="name" type="text" class="form-control" placeholder="{{ trans('translation.name_en') }}" value="{{ $category->getTranslation('name', 'en') }}" required>
                                            @error('name')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>   
   
                                    <div class="form-group row mb-4">
                                        <label for="name_ar" class="col-form-label col-lg-2">{{ trans('translation.name_ar') }}</label>
                                        <div class="col-lg-10">
                                            <input id="name_ar" name="name_ar" type="text" class="form-control" placeholder="{{ trans('translation.name_ar') }}" value="{{ $category->getTranslation('name', 'ar') }}" required>
                                            @error('name_ar')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="taskbudget" class="col-form-label col-lg-2">{{ trans('translation.image') }}</label>
                                        <div class="col-lg-3">
                                            <div class="card p-1 border shadow-none">
                                                <div class="position-relative">
                                                    <img class="rounded" width="50" height="50" src="{{ $category->image }}">

                                                </div>
                                            </div>
                                            <input type="file" name="image" id="image" class="form-control">
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-lg-2">{{ trans('translation.status') }}</label>
                                        <div class="form-check form-switch form-switch-lg mb-3 col-md-2" dir="ltr">
                                            <input class="form-check-input" type="checkbox" id="SwitchCheckSizelg" name="status" {{ ($category->status == 1)  ? 'checked' : '' }}>
                                        </div>
                                    </div>

                            </div>
                            <div class="row justify-content-end">
                                <div class="col-lg-10">
                                    <button type="submit" class="btn btn-primary"> {{  trans('translation.update') }} </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

@endsection


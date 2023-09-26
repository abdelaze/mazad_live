@extends('layouts.backend.master')
@section('title') {{ trans('translation.onbaordings') }} @endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ trans('translation.add_onbaording') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('translation.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('translation.onboardings') }}</li>
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
                        <h4 class="card-title mb-4">{{ trans('translation.create_new_onbaording') }}</h4>
                        <form  action="{{route('onboardings.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div data-repeater-list="outer-group" class="outer">
                                <div data-repeater-item class="outer">
                                    <div class="form-group row mb-4">
                                        <label for="name" class="col-form-label col-lg-2">{{ trans('translation.title_en') }}</label>
                                        <div class="col-lg-10">
                                            <input id="title" name="title" type="text" class="form-control" placeholder="{{ trans('translation.title_en') }}" value="{{ old('title') }}" required>
                                            @error('title')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>   
   
                                    <div class="form-group row mb-4">
                                        <label for="title_ar" class="col-form-label col-lg-2">{{ trans('translation.title_ar') }}</label>
                                        <div class="col-lg-10">
                                            <input id="title_ar" name="title_ar" type="text" class="form-control" placeholder="{{ trans('translation.title_ar') }}" value="{{ old('title_ar') }}" required>
                                            @error('title_ar')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="content" class="col-form-label col-lg-2">{{ trans('translation.content_en') }}</label>
                                        <div class="col-lg-10">
                                            <textarea id="content" name="content" class="form-control" placeholder="{{ trans('translation.content_en') }}" required> {{ old('content') }} </textarea>
                                            @error('content')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="content_ar" class="col-form-label col-lg-2">{{ trans('translation.content_ar') }}</label>
                                        <div class="col-lg-10">
                                            <textarea id="content_ar" name="content_ar" class="form-control" placeholder="{{ trans('translation.content_ar') }}" required> {{ old('content_ar') }} </textarea>
                                            @error('content_ar')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="taskbudget" class="col-form-label col-lg-2">{{ trans('translation.image') }}</label>
                                        <div class="col-lg-10">
                                            <input type="file" name="image" id="image" class="form-control" required>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
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

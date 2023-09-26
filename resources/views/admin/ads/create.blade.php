@extends('layouts.backend.master')
@section('title') @lang('translation.add_new_ad') @endsection
@push('css')
    <!-- select2 css -->
    <link href="{{asset(ASSET_PATH.'assets/backend/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- dropzone css -->
    <link href="{{ asset(ASSET_PATH.'assets/backend/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

      
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ trans('translation.add_new_ad') }}</h4>
                        <form  action="{{route('ads.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div data-repeater-list="outer-group" class="outer">
                                <div data-repeater-item class="outer">
                                    <div class="form-group row mb-4">
                                        <label for="name" class="col-form-label col-lg-2">{{ trans('translation.url') }}</label>
                                        <div class="col-lg-10">
                                            <input id="link" name="link" type="text" class="form-control" placeholder="Ad Url" value="{{ old('link') }}" required>
                                            @error('link')
                                                <span style="color: red"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="taskbudget" class="col-form-label col-lg-2">{{ trans('translation.image') }}</label>
                                        <div class="col-lg-10">
                                            <input type="file" name="image" id="image" class="form-control">
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

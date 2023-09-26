@extends('layouts.backend.master')
@section('title') {{  __('translation.subcategory_attributes') }} @endsection

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{__('translation.create new attribute')}}</h4>
                    <form class="repeater" action="{{route('sub-attributes.inputs.preview')}}" method="POST" id="generate-new-input">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{__('translation.enter label')}}:</label>
                                    <input type="text" name="input_label" class="form-control" placeholder="{{__('translation.enter label')}}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{__('translation.enter label_in_arabic')}}:</label>
                                    <input type="text" name="input_label_ar" class="form-control" placeholder="{{__('translation.enter label_in_arabic')}}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{__('translation.select input type')}}:</label>
                                    <select class="form-control select2" name="input_type" required>
                                        <option disabled selected>{{__('translation.select input type')}}</option>
                                        <option value="text">Text</option>
                                        {{--<option value="textarea">{{__('translation.textarea')}}</option>--}}
                                        <option value="number">Number</option>
                                        <option value="color">Color</option>
                                        <option value="radio">Radio</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="select">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">{{__('translation.input name')}}:</label>
                                    <input type="text" name="input_name" class="form-control" placeholder="{{__('translation.input name')}}" required>
                                </div>
                            </div>
                           
                        </div>

                        <div class="input-details"></div>

                        <div class="mb-2 text-center">
                            <div class="spinner-border text-primary m-1 d-none" role="status"><span class="sr-only"></span></div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">{{__('translation.preview')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4 text-center">* {{__('translation.preview input')}} *</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 preview"></div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@include('admin.sub-attributes.scripts.preview-input-html')
@include('admin.sub-attributes.scripts.detect-input-radio-selected')
@endpush

@extends('layouts.backend.master')
@section('title') {{ trans('translation.brands') }} @endsection

@section('content')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ trans('translation.add_brand') }}</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('translation.Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('translation.brands') }}</li>
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
                        <h4 class="card-title mb-4">{{ trans('translation.create_new_brand') }}</h4>
                        <form  action="{{route('brands.store')}}" method="POST" enctype="multipart/form-data">
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
                                        <label for="taskbudget" class="col-form-label col-lg-2">{{ trans('translation.image') }}</label>
                                        <div class="col-lg-10">
                                            <input type="file" name="image"  id="image" class="form-control" required>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label for="category_id" class="col-form-label col-lg-2">{{ trans('translation.category') }}</label>
                                        <div class="col-lg-10">
                                            <select class="form-control select2" id="category-select" name="category_id" required>
                                                <option value="">{{ trans('translation.select_category') }}</option>
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
                                        <label for="category_id" class="col-form-label col-lg-2">{{ trans('translation.subcategory') }}</label>
                                        <div class="col-lg-10">
                                            <select class="form-control select2" id="subcategory-select" name="sub_category_id" required>
                                                <option value="">{{ trans('translation.select_subcategory') }}</option>
                                            </select>
                                        </div>
                                        @error('sub_category_id')
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


@push('script')

   <script>
        $(document).ready(function() {
            $('#category-select').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: "{{route('categories.subcat')}}",
                        data: { categoryId : categoryId, _token: '{{ csrf_token() }}'},
                        type: 'POST',
                        dataType: 'json',
                        success: function(data) {
                            $('#subcategory-select').empty();
                            $('#subcategory-select').html(data.options);
                        }
                    });
                } else {
                    $('#subcategory-select').empty();
                    $('#subcategory-select').append('<option value="">'+trans('translation.select_sub_category')+'</option>');
                }
            });
        });
    </script>

@endpush

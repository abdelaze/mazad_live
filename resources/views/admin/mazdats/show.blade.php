@extends('layouts.backend.master')
@section('title') @lang('translation.mazdats') @endsection

@push('css')
      <!-- DataTables -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <!--  Responsive datatable examples -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
  
       <!-- start page title -->
       <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">{{ trans('translation.mazad_details') }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="product-detai-imgs">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-4">
                                        <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                           @foreach ($mazad->images as $key=>$image)
                                                <a @if($key == 0)  class="nav-link active" @else class="nav-link"  @endif id="product-{{ $image->id }}-tab" data-bs-toggle="pill" href="#product-{{ $image->id }}" role="tab" aria-controls="product-{{ $image->id }}"  @if($key == 0) aria-selected="true" @else  aria-selected="false"  @endif>
                                                    <img src="{{ $image->image }}" alt="" class="img-fluid mx-auto d-block rounded">
                                                </a>
                                            @endforeach
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                        <div class="tab-content" id="v-pills-tabContent">

                                            @foreach ($mazad->images as $key=>$image)
                                                <div @if($key == 0) class="tab-pane fade show active" @else class="tab-pane fade"  @endif  id="product-{{ $image->id }}" role="tabpanel" aria-labelledby="product-{{ $image->id }}-tab">
                                                    <div>
                                                        <img src="{{ $image->image }}" alt="" class="img-fluid mx-auto d-block">
                                                    </div>
                                                </div>
                                            @endforeach
                                          
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="mt-4 mt-xl-3">
                                <a href="javascript: void(0);" class="text-primary">{{ $mazad->product_name }}</a>
                                <h4 class="mt-1 mb-3">{{ $mazad->product_desc }}</h4>

                                <p class="text-muted float-start me-3">
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star text-warning"></span>
                                    <span class="bx bxs-star"></span>
                                </p>
                                <p class="text-muted mb-4"> {{ trans('translation.views') }}      :  {{ $mazad->views }} </p>
                                <h5 class="mb-4">{{ trans('translation.is_open') }}      : 
                                    
                                    @if($mazad->is_open == 1) 
                                          <span class="badge badge-pill badge-soft-success font-size-12">{{  trans('translation.yes') }} </span>
                                     @else 
                                          <span class="badge badge-pill badge-soft-danger font-size-12">{{   trans('translation.no') }} </span>
                                     @endif
                                
                               </h5>
                                <h5 class="mb-4">{{ trans('translation.is_close') }}     :
                                    @if($mazad->is_closed == 1) 
                                    <span class="badge badge-pill badge-soft-success font-size-12">{{  trans('translation.yes') }} </span>
                                    @else 
                                            <span class="badge badge-pill badge-soft-danger font-size-12">{{   trans('translation.no') }} </span>
                                    @endif
                                </h5>
                                
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="mt-5">
                        <h5 class="mb-3"> {{ trans('translation.user_details') }} :</h5>

                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 400px;"> {{ trans('translation.full_name') }}</th>
                                        <td>{{ $mazad->user->full_name}}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" style="width: 400px;"> {{ trans('translation.email') }}</th>
                                        <td>{{ $mazad->user->email}}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" style="width: 400px;"> {{ trans('translation.phone_number') }}</th>
                                        <td>{{ $mazad->user->phone_number}}</td>
                                    </tr>

                                     
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end Specifications -->

                    <div class="mt-5">
                        <h5 class="mb-3"> {{ trans('translation.specifications') }} :</h5>

                        <div class="table-responsive">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 400px;"> {{ trans('translation.category') }}</th>
                                        <td>{{ $mazad->category?->name}}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" style="width: 400px;"> {{ trans('translation.subcategory') }}</th>
                                        <td>{{ $mazad->subcategory?->name}}</td>
                                    </tr>

                                     
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end Specifications -->
                 

                </div>
            </div>
            <!-- end card -->
        </div>
    </div>
    <!-- end row -->

 

@endsection

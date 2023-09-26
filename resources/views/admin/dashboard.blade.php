@extends('layouts.backend.master')
@section('title') @lang('translation.Dashboard') @endsection
@section('content')
 <!-- start page title -->
 <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ trans('translation.Dashboard')}}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{trans('translation.Dashboard')}}</a></li>
                    <li class="breadcrumb-item active">{{ trans('translation.Dashboard')}}</li>
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
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <img src="{{asset('assets/backend/images/profile-img.png')}}" alt="" class="avatar-md rounded-circle img-thumbnail">
                                    </div>
                                    <div class="flex-grow-1 align-self-center">
                                        <div class="text-muted">
                                            <p class="mb-2">{{ trans('translation.welcome_to_mazdat')}}</p>
                                            <h5 class="mb-1"> {{ auth()->user()->user_name }} </h5>
                                            <p class="mb-0"> {{ trans('translation.Dashboard_Admin')}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 align-self-center">
                                <div class="text-lg-center mt-4 mt-lg-0">
                                    <div class="row">
                                        <div class="col-4">
                                            <div>
                                                <p class="text-muted text-truncate mb-2">{{ trans('translation.mazdats')}}</p>
                                                <h5 class="mb-0">{{ \App\Models\Mazdat::count(); }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div>
                                                <p class="text-muted text-truncate mb-2">{{ trans('translation.products')}}</p>
                                                <h5 class="mb-0"> {{ \App\Models\Product::count(); }} </h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div>
                                                <p class="text-muted text-truncate mb-2">{{ trans('translation.users')}}</p>
                                                <h5 class="mb-0">{{ \App\Models\User::count(); }}</h5>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                        <!-- end row -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-copy-alt"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0">{{ trans('translation.orders')}}</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4>1,452 <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-archive-in"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0">{{ trans('translation.revenue')}}</h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4>$ 28,452 <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="bx bx-purchase-tag-alt"></i>
                                        </span>
                                    </div>
                                    <h5 class="font-size-14 mb-0"> {{ trans('translation.ads')}} </h5>
                                </div>
                                <div class="text-muted mt-4">
                                    <h4> {{ \App\Models\Ad::count(); }} <i class="mdi mdi-chevron-up ms-1 text-success"></i></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-end">
                                <div class="input-group input-group-sm">
                                    <select class="form-select form-select-sm">
                                        <option value="JA" selected>Jan</option>
                                        <option value="DE">Dec</option>
                                        <option value="NO">Nov</option>
                                        <option value="OC">Oct</option>
                                    </select>
                                    <label class="input-group-text">Month</label>
                                </div>
                            </div>
                            <h4 class="card-title mb-4">Earning</h4>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="text-muted">
                                    <div class="mb-4">
                                        <p>This month</p>
                                        <h4>$2453.35</h4>
                                        <div><span class="badge badge-soft-success font-size-12 me-1"> + 0.2% </span> From previous period</div>
                                    </div>

                                    <div>
                                        <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View Details <i class="mdi mdi-chevron-right ms-1"></i></a>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <p class="mb-2">Last month</p>
                                        <h5>$2281.04</h5>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div id="line-chart" class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Sales Analytics</h4>

                        <div>
                            <div id="donut-chart" class="apex-charts"></div>
                        </div>

                        <div class="text-center text-muted">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-primary me-1"></i> {{ trans('translation.products')}} </p>
                                        <h5>$ 2,132</h5>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mt-4">
                                        <p class="mb-2 text-truncate"><i class="mdi mdi-circle text-success me-1"></i>{{ trans('translation.mazdats')}}</p>
                                        <h5>$ 1,763</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!-- end row -->



<!-- end row -->
</div>
<!-- container-fluid -->
</div>
<!-- End Page-content -->

@endsection


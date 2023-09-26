@extends('layouts.backend.master')
@section('title') @lang('translation.Details') @endsection

@push('css')

      <!-- DataTables -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <!--  Responsive datatable examples -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')


    <div class="row">

        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3 align-self-center">
                                    <i class="mdi mdi-bitcoin h2 text-warning mb-0"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-2">Coupons Amount</p>
                                    <h5 class="mb-0">{{ $data->CouponReservations->sum('coupon_discount') }} </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3 align-self-center">
                                    <i class="mdi mdi-ethereum h2 text-primary mb-0"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-2">Paid Amount</p>
                                    <h5 class="mb-0"> {{ !empty($data->bills()->where('paid_status' , 'paid')->sum('paid_amount')  ) ? $data->bills()->where('paid_status' , 'paid')->sum('paid_amount')  : 0 }} </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3 align-self-center">
                                    <i class="mdi mdi-litecoin h2 text-info mb-0"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-2">Remain Amount</p>
                                    <h5 class="mb-0"> {{ !empty($data->bills()->where('paid_status' , 'paid')->sum('paid_amount')  ) ? $data->CouponReservations->sum('coupon_discount') - $data->bills()->where('paid_status' , 'paid') ->sum('paid_amount') : $data->CouponReservations->sum('coupon_discount') }} </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- end row -->

        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Bill Details</h4>
                    <div class="mt-4">
                       <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive w-100" data-orderable="0" data-orderable_type="asc">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Salon</th>
                                    <th>Paid Amount</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($data->bills) && count($data->bills) > 0)
                                      @foreach ($data->bills as $key => $d)

                                        @if($d->paid_status == 'paid')
                                            <tr style="background:#4C8BF5 !important;color:#fff !important;">
                                                <td>{{ $d->id }}</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $d->paid_amount }}</td>
                                                <td>
                                                    <p style="visibility:hidden;height:0 ;margin:0">{{$d->paid_date}}</p>
                                                    {{ date('d/m/Y', strtotime($d->paid_date))  }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td> {{ $d->id }} </td>
                                                <td> {{ $data->name }} </td>
                                                <td> {{ $d->paid_amount }} </td>
                                                <td>
                                                    <p style="visibility:hidden;height:0 ;margin:0">{{$d->created_at}}</p>
                                                    {{ date('d/m/Y', strtotime($d->created_at->addHours(2)))  }}
                                                </td>
                                            </tr>
                                        @endif

                                      @endforeach
                                  @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->



@endsection
@push('script')
  <!-- Required datatable js -->
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <!-- Buttons examples -->
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/jszip/jszip.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/pdfmake/build/pdfmake.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/pdfmake/build/vfs_fonts.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

  <!-- Responsive examples -->
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
  <!-- Datatable init js -->
  <script src="{{asset(ASSET_PATH.'assets/backend/js/pages/datatables.init.js')}}"></script>
   <!-- apexcharts -->

   @endpush

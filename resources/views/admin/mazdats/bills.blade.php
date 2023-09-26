@extends('layouts.backend.master')
@section('title') @lang('translation.bills') @endsection

@push('css')

      <!-- DataTables -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <!--  Responsive datatable examples -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
                            <p class="text-muted mb-2">{{   __('translation.Users_Money') }} </p>
                            <h5 class="mb-0">{{  \App\Models\MazadSelectedUser::where('payment_status' , 'paid')->sum('price')   }} </h5>
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
                            <p class="text-muted mb-2">{{   __('translation.Paid_Amount') }} </p>
                            <h5 class="mb-0"> {{  \App\Models\MazadSelectedUser::where('payment_status' , 'paid')->sum('paid_amount')   }} </h5>
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
                            <p class="text-muted mb-2">{{   __('translation.Remain_Amount') }}</p>
                            <h5 class="mb-0"> {{  \App\Models\MazadSelectedUser::where('payment_status' , 'paid')->sum('price')  -  \App\Models\MazadSelectedUser::where('payment_status' , 'paid')->sum('paid_amount')   }}  </h5>
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
    <div class="card">
            <div class="card-body">

                <div class="table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                    <table id="datatable" class="table  table-bordered table-responsive table-striped   table-hover" data-orderable="0" data-orderable_type="desc">
                            <thead>
                                <tr>
                                    <th>{{   __('translation.no') }}</th>
                                    <th>{{   __('translation.User') }}</th>
                                    <th>{{   __('translation.phone_number') }}</th>
                                    <th>{{   __('translation.Mazad_Name') }}</th>
                                    <th>{{   __('translation.Reserved_Amount') }}</th>
                                    <th>{{   __('translation.Paid_Amount') }}</th>
                                    <th>{{   __('translation.Remain_Amount') }}</th>
                                    <th>{{   __('translation.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data) && count($data) > 0)
                                @foreach ($data as $key=>$user)
                                     <?php 
                                    // dd($user);
                                     ?>
                                    @if(!empty($user->selectedMazdats[0]['mazad'])) 
                                    <tr>
                                        <td>{{ $key+1}}</td>
                                        <td>{{ $user->full_name}} </td>
                                        <td>{{ $user->phone_number}} </td>
                                        <td onclick="window.open('{{ route('mazdats.show', $user->selectedMazdats[0]['mazad']->id) }}','_blank')"
                                            style=" cursor:pointer;">{{ $user->selectedMazdats[0]['mazad']->product_name }}</td>
                                        <td>{{ $user->selectedMazdats[0]['price'] }}</td>
                                        <td>{{ $user->selectedMazdats[0]['paid_amount'] }}</td>
                                        <td>{{ $user->selectedMazdats[0]['price']  -  $user->selectedMazdats[0]['paid_amount']  }}</td>
                                        <td>

                                            <button class="btn btn-sm btn-danger pay"    data-bs-toggle="modal" data-bs-target="#paid"   data-user_id="{{$user->id}}"  data-id="{{$user->selectedMazdats[0]['mazad']->id}}" > {{   __('translation.Pay') }} </button>

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

</div> <!-- end row -->


<!--New Modale -->
<div class="modal fade" id="paid" tabindex="-1" aria-labelledby="paidLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 0;padding: 13px;">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{route('mazad_user.pay.bill')}}" >
                @csrf
                    <div class="modal-body questionare">

                        <input  type="hidden" name="mazad_id" id ="mazad_id" >
                        <input  type="hidden" name="user_id" id  ="user_id" >
                       <div class="justify-content-center mb-1 step-2 step" >
                           <h3 class="text-center mb-3"> {{   __('translation.Enter_The_Amount_You_Want_To_Pay') }}    </h3>
                           <div class="row justify-content-center replayoptions">

                                    <div class="col-md-10">
                                        <label  for="amount">{{   __('translation.Amount') }} </label>
                                        <input type="number"  id="amount" name="amount"  class="form-control" >
                                    </div>
                           </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{   __('translation.Pay') }} </button>
                    </div>
          </form>
        </div>
    </div>
</div>

<!--New Modale-->


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

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("input[type=datetime-local]");
</script>



  <script>
        $(document).unbind().on('click', '.pay', function () {
              var id      = $(this).data('id');
              var user_id = $(this).data('user_id');
              $('#mazad_id').val(id) ;
              $('#user_id').val(user_id) ;
              $('#paid').modal('show');
          });
          /* Change  Client  That Attended  */
  </script>
@endpush

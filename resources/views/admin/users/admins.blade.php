@extends('layouts.backend.master')
@section('title') @lang('translation.Admins') @endsection
 
@push('css')
  
      <!-- DataTables -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
      <!--  Responsive datatable examples -->
      <link href="{{asset(ASSET_PATH.'assets/backend/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />  
@endpush
@section('content')


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <h4 class="card-title">{{ trans('translation.Admins') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <a href="{{ route('users.create') }}" class="btn btn-info btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i>  {{ trans('translation.add_admin') }} </a>
                        </div>
                    </div><!-- end col-->
                </div>
              
                <table id="users_datatable" class="table table-bordered dt-responsive  w-100">
                    <thead>
                    <tr>
                    
                        <th>{{ trans('translation.no') }}</th>
                        <th>{{ trans('translation.user_name') }}</th>
                        <th>{{ trans('translation.email') }}</th>
                        <th>{{ trans('translation.status') }}</th>
                        <th>{{ trans('translation.action') }}</th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


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
  
  <script>
    $(document).ready(function(){
      let User_Lang = "{{app()->getLocale()}}";
      let localeUrl = User_Lang === 'ar' ? '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json' : '//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json';
      $("#users_datatable").DataTable(
      { 
              language: {
               url: localeUrl
              },
              processing: true,
              serverSide: true,
              ajax: "{{ route('admins.index') }}",
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'user_name'      ,  name:  'user_name'},
                  {data: 'email'          ,  name:  'email'},
                  {data: 'status'         ,  name:  'status' },
                  {data: 'action'        , name: 'action', orderable: false, searchable: false},
              ] 
      });
    
    });
</script>

<script>
  $(document).ready(function () {
         
      $(document).on("click",".update_status",function() {
                  $(this).on('change', function (event) {
                          var user_id              = $(this).data('id');
                          $.ajax({
                              type: 'POST',
                              url: '{{route('admin.update_admin_status')}}',
                              data: { user_id : user_id, _token: '{{ csrf_token() }}'},
                              success: function (data) {

                                  flasher.success('{{trans("translation.status_updated_successfully")}}','{{trans("translation.success")}}');

                              }
                          });
                  });
          });
         
  });
</script>


@endpush
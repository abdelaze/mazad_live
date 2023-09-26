@extends('layouts.backend.master')
@section('title') @lang('translation.Users') @endsection
 
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

                <th>{{ trans('translation.all_users') }}</th>
              
                <table id="users_datatable" class="table table-bordered dt-responsive  w-100">
                    <thead>
                    <tr>
                    
                        <th>{{ trans('translation.no') }}</th>
                        <th>{{ trans('translation.full_name') }}</th>
                        <th>{{ trans('translation.user_name') }}</th>
                        <th>{{ trans('translation.phone_number') }}</th>
                        <th>{{ trans('translation.email') }}</th>
                        <th>{{ trans('translation.status') }}</th>
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
              ajax: "{{ route('users.index') }}",
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'full_name'      ,  name:  'full_name'},
                  {data: 'user_name'      ,  name:  'user_name'},
                  {data: 'phone_number'   ,  name:  'phone_number'},
                  {data: 'email'          ,  name:  'email'},
                  {data: 'status'         ,  name:  'status' },
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
                              url: '{{route('admin.update_user_status')}}',
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
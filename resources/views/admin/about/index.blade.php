@extends('layouts.backend.master')
@section('title') @lang('translation.about') @endsection

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
                                <h4 class="card-title">{{ trans('translation.about') }}</h4>
                               
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <a href="javascript:void(0)" id="createNewData" class="btn btn-info btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i>   {{ trans('translation.add_about') }} </a>
                        </div>
                    </div><!-- end col--> 
                </div>

               
                    <div class="table-responsive">
                        <table id="about_datatable"  class="table align-middle table-nowrap table-check">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ trans('translation.no') }}</th>
                                    <th>{{ trans('translation.details') }}</th>
                                    <th>{{ trans('translation.action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="bodycontent"style="cursor:pointer">
                                    @if(!empty($datas ) && count($datas ) > 0)
                                    @foreach ($datas  as $key=>$data)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $data->detail }}</td>
                                            <td>
                                                <a href="javascript:void(0)" data-toggle="tooltip"  data-id="{{ $data->id }}" data-original-title="Edit" class="edit btn btn-primary btn-sm editData">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                    
                                                {!! Form::open(['method' => 'DELETE','route' => ['abouts.destroy', $data->id],'style'=>'display:inline']) !!}
                                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                {!! Form::close() !!}
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                        </table>


                        <div class="modal fade" id="ajaxModel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modelHeading"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="dataForm" name="dataForm" class="form-horizontal">
                                           <input type="hidden" name="id" id="id">
                                           
                                            <div class="form-group row mb-4">
                                                <label class="col-sm-2 control-label"> {{ trans('translation.details') }} </label>
                                                <div class="col-sm-12">
                                                    <textarea id="detail" name="detail" required="" placeholder="{{ trans('translation.enter_details') }}" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row mb-4">
                                                <label class="col-sm-2 control-label"> {{ trans('translation.details_ar') }} </label>
                                                <div class="col-sm-12">
                                                    <textarea id="detail_ar" name="detail_ar" required="" placeholder="{{ trans('translation.enter_details') }}" class="form-control"></textarea>
                                                </div>
                                            </div>
                                
                                            <div class="justify-content-end">
                                             <button type="submit" class="btn btn-primary" id="saveBtn" value="create">{{ trans('translation.save') }}
                                             </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
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
      $(document).ready(function() {
            let User_Lang = "{{app()->getLocale()}}";
            let localeUrl = User_Lang === 'ar' ? '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json' : '//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json';
           var table =  $("#about_datatable").DataTable(
                { 
                    language: {
                    url : localeUrl
                },

            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN':  '{{ csrf_token() }}',
                    }
            });
            
            /*------------------------------------------
            --------------------------------------------
            Render DataTable
            --------------------------------------------
            --------------------------------------------*/
            
            /*------------------------------------------
            --------------------------------------------
            Click to Button
            --------------------------------------------
            --------------------------------------------*/
            $('#createNewData').click(function () {
                $('#saveBtn').val("{{trans('translation.save')}}");
                $('#id').val('');
                $('#dataForm').trigger("reset");
                $('#modelHeading').html("{{trans('translation.add_new_data')}}");
                $('#ajaxModel').modal('show');
            });
            
            /*------------------------------------------
            --------------------------------------------
            Click to Edit Button
            --------------------------------------------
            --------------------------------------------*/
            $('body').on('click', '.editData', function () {
            var id = $(this).data('id');
            $.get("{{ route('abouts.index') }}" +'/' + id +'/edit', function (data) {
                $('#modelHeading').html("{{trans('translation.edit')}}");
                $('#saveBtn').val("{{trans('translation.edit')}}");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#detail').val(data.detail['en']);
                $('#detail_ar').val(data.detail['ar']);
            })
            });
            
            /*------------------------------------------
            --------------------------------------------
            Create Product Code
            --------------------------------------------
            --------------------------------------------*/
            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html("{{trans('translation.Sending..')}}");
            
                $.ajax({
                data: $('#dataForm').serialize(),
                url: "{{ route('abouts.store') }}",
                type: "POST",
                dataType: 'json',
                _token     :  '{{ csrf_token() }}',
                success: function (data) {
            
                    $('#dataForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    location.reload();
                
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html("{{trans('translation.save')}}");
                }
            });
            });
            
           

      });



      
 </script>
  
@endpush

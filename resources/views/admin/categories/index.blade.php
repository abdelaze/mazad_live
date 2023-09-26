@extends('layouts.backend.master')
@section('title') @lang('translation.categories') @endsection

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
                                <h4 class="card-title">{{ trans('translation.all_categories') }}</h4>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <a href="{{ route('cat-import-view') }}" class="btn btn-info btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i>   {{ trans('translation.Import_Excel_Sheet') }} </a>
                        </div>
                    </div><!-- end col-->
                </div>

               
                    <div class="table-responsive">
                        <table id="cat_datatable"  class="table align-middle table-nowrap table-check">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ trans('translation.no') }}</th>
                                    <th>{{ trans('translation.name') }}</th>
                                    <th>{{ trans('translation.image') }}</th>
                                    <th style="display:none;">{{ trans('translation.position') }}</th>
                                    <th>{{ trans('translation.status') }}</th>
                                    <th>{{ trans('translation.action') }}</th>
                                </tr>
                                </thead>
                                <tbody id="bodycontent"style="cursor:pointer">
                                    @if(!empty($cats ) && count($cats ) > 0)
                                    @foreach ($cats  as $cat)
                                        <tr class="row1" data-id="{{ $cat->id }}">
                                            <td>{{ $cat->id }}</td>
                                            <td>{{ $cat->name }}</td>
                                            <td><img class="rounded" width="50" height="50" src="{{$cat->image}}"></td>
                                            <td style="display:none;">{{ $cat->position }}</td>
                                            <td> @if($cat->status == 1)   <button type="button" class="btn btn-outline-success waves-effect waves-light">{{trans('translation.active')}}</button> @else  <button type="button" class="btn btn-outline-danger waves-effect waves-light">{{trans('translation.not_active')}}</button> @endif</td>
                                            <td>
                                                <a class="btn btn-secondary btn-sm edit" href="{{ route('categories.edit',$cat->id) }}"  title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                    
                                                {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy', $cat->id],'style'=>'display:inline']) !!}
                                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                {!! Form::close() !!}

                                                <a class="btn btn-info btn-sm edit" href="{{ route('category.show_attributes',$cat->id) }}"  title="View Attributes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                        </table>
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
      $(document).ready(function(){
        let User_Lang = "{{app()->getLocale()}}";
        let localeUrl = User_Lang === 'ar' ? '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json' : '//cdn.datatables.net/plug-ins/1.10.24/i18n/English.json';
        $("#cat_datatable").DataTable(
            { 
                language: {
                 url: localeUrl
            },

            "order" : [3 ,'asc'],
           
        });


       $('#bodycontent').sortable({
                items : "tr" ,
                cursor : "move" ,
                opacity : 0.6 ,
                start: function( event, ui ) {
                    cuurentItem = $(ui.item).data('id');
                    cuurentItemIndex = ui.item.index();
                },
                update: function (event,ui){
                   // newposition = ui.item.index() ;
                     newposition = ui.item.index() ;
                    sendOrderToServer(cuurentItem , newposition) ;
                 },
        }) ;

        function sendOrderToServer(cuurentItem , newposition ) {
                var slecteddata = [];
                $('tr.row1').each(function(index,element) {
                    slecteddata.push($(this).attr('data-id'));
                });
                // the Ajax Post update
                $.ajax({ 
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('categories.sort') }}",
                    data: {
                        newposition : newposition ,
                        cuurentItem : cuurentItem ,
                        cuurentItemIndex : cuurentItemIndex , 
                        slecteddata: slecteddata,
                        _token     :  '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            console.log(response);
                        } else {
                            console.log(response);
                        }
                    }
                });
            }


       /*     $( "#bodycontent" ).sortable({
          items: "tr",
          cursor: 'move',
          opacity: 0.6,
          update: function() {
              sendOrderToServer();
          }
        });

        function sendOrderToServer() {
          var order = [];
          var token = $('meta[name="csrf-token"]').attr('content');
          $('tr.row1').each(function(index,element) {
            console.log(element);
            order.push({
              id: $(this).attr('data-id'),
              position: index+1
            });
          });

          $.ajax({
            type: "POST", 
            dataType: "json", 
            url: "{{ route('categories.sort') }}",
                data: {
              order: order,
              _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status == "success") {
                  console.log(response);
                } else {
                  console.log(response);
                }
            }
          });
        }*/



      });
 </script>
  
@endpush

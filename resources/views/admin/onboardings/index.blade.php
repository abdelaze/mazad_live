@extends('layouts.backend.master')
@section('title') @lang('translation.onboardings') @endsection

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

                <h4 class="card-title">{{ trans('translation.all_noboardings') }}</h4>
             
               
                  
                        <div class="table-responsive">
                            <table id="onboard_datatable"  class="table align-middle table-nowrap table-check">
                                <thead class="table-light">
                               
                                    <tr>
                                        <th>{{ trans('translation.no') }}</th>
                                        <th>{{ trans('translation.title') }}</th>
                                        <th>{{ trans('translation.content') }}</th>
                                        <th>{{ trans('translation.image') }}</th>
                                        <th>{{ trans('translation.status') }}</th>
                                        <th>{{ trans('translation.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($onboardings) && count($onboardings) > 0)
                                        @foreach ($onboardings as $key=>$onboarding)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $onboarding->title }}</td>
                                                <td>{{ $onboarding->content }}</td>
                                                <td><img class="rounded" width="50" height="50" src="{{$onboarding->image}}"></td>
                                                <td> @if($onboarding->status == 1)   <button type="button" class="btn btn-outline-success waves-effect waves-light">{{trans('translation.active')}}</button> @else  <button type="button" class="btn btn-outline-danger waves-effect waves-light">{{trans('translation.not_active')}}</button> @endif</td>
                                                <td>
                                                    <a class="btn btn-secondary btn-sm edit" href="{{ route('onboardings.edit',$onboarding->id) }}"  title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                        
                                                    {!! Form::open(['method' => 'DELETE','route' => ['onboardings.destroy', $onboarding->id],'style'=>'display:inline']) !!}
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                {!! Form::close() !!}
                                                
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
      $("#onboard_datatable").DataTable(
          { 
              language: {
               url: localeUrl
          }} 
      );
    });
</script>

@endpush

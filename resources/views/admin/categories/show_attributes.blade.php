@extends('layouts.backend.master')
@section('title') {{ trans('translation.categories') }} @endsection

@section('content')
 
                        <div class="row">
                            @foreach ($attributes as $attribute)
                                @if($attribute->input_type === 'text')
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                    
                                                        <div class="flex-grow-1 overflow-hidden">

                                                            <h5 class="text-truncate font-size-15">
                                                                  <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                                  {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                                  <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                                {!! Form::close() !!}
                                                            </h5>
                                                            <p class="text-muted mb-4"><input type="text" name="{{ $attribute->input_name }}" class="form-control"></p>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                               @endif

                               @if($attribute->input_type === 'number')
                                        <div class="col-xl-4 col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                    
                                                        <div class="flex-grow-1 overflow-hidden">

                                                            <h5 class="text-truncate font-size-15">
                                                                <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                                {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                                  <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                                {!! Form::close() !!}
                                                            </h5>
                                                            <p class="text-muted mb-4"><input type="number" name="{{ $attribute->input_name }}" class="form-control"></p>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                               @endif

                               @if($attribute->input_type === 'color')
                               <div class="col-xl-4 col-sm-6">
                                   <div class="card">
                                       <div class="card-body">
                                           <div class="d-flex">
                                           
                                               <div class="flex-grow-1 overflow-hidden">

                                                   <h5 class="text-truncate font-size-15">
                                                    <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                                  <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                    {!! Form::close() !!}
                                                   </h5>
                                                   <p class="text-muted mb-4"><input type="color" name="{{ $attribute->input_name }}" class="form-control"></p>
                                                   
                                               </div>
                                           </div>
                                       </div>
                                   
                                   </div>
                               </div>
                            @endif
                       
                             @if($attribute->input_type === 'textarea')
                               <div class="col-xl-4 col-sm-6">
                                   <div class="card">
                                       <div class="card-body">
                                           <div class="d-flex">
                                           
                                               <div class="flex-grow-1 overflow-hidden">

                                                   <h5 class="text-truncate font-size-15">
                                                     <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                     {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                     <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                   {!! Form::close() !!}    
                                                </h5>
                                                   <p class="text-muted mb-4"><textarea name="{{ $attribute->input_name }}" class="form-control"></textarea></p>
                                                   
                                               </div>
                                           </div>
                                       </div>
                                   
                                   </div>
                               </div>
                            @endif


                              @if($attribute->input_type === 'radio')
                               <div class="col-xl-4 col-sm-6">
                                   <div class="card">
                                       <div class="card-body">
                                           <div class="d-flex">
                                           
                                               <div class="flex-grow-1 overflow-hidden">

                                                   <h5 class="text-truncate font-size-15">
                                                       <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                       {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                       <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                     {!! Form::close() !!}
                                                       
                                                    </h5>
                                                   <p class="text-muted mb-4">
                                                     
                                                       @if(\App::getLocale()  == "en")
                                                        @foreach($attribute->options as $key=>$radio)
                                                       
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="radio" name="formRadios" id="formRadios1" >
                                                            <label class="form-check-label" for="formRadios1">
                                                                {{  $radio }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                        @else 
                                                        @foreach($attribute->options_ar as $key=>$radio2)
                                                       
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="radio" name="formRadios" id="formRadios1" >
                                                            <label class="form-check-label" for="formRadios1">
                                                                {{  $radio2 }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    
                                                   </p>
                                                   
                                               </div>
                                           </div>
                                       </div>
                                   
                                   </div>
                               </div>
                            @endif


                            @if($attribute->input_type === 'checkbox')
                                <div class="col-xl-4 col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex">
                                            
                                                <div class="flex-grow-1 overflow-hidden">

                                                    <h5 class="text-truncate font-size-15">
                                                        
                                                        <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                        {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                                  <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                       {!! Form::close() !!}
                                                       
                                                    </h5>
                                                    <p class="text-muted mb-4">
                                                    
                                                        @if(\App::getLocale()  == "en")
                                                        @foreach($attribute->options as $key=>$checkbox)
                                                        
                                                            <div class="form-check form-check-success mb-3">
                                                                <input class="form-check-input" type="checkbox" id="formCheckcolor2" checked="">
                                                                <label class="form-check-label" for="formCheckcolor2">
                                                                    {{  $checkbox }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                        @else 
                                                        @foreach($attribute->options_ar as $key=>$checkbox2)
                                                            <div class="form-check form-check-success mb-3">
                                                                <input class="form-check-input" type="checkbox" id="formCheckcolor2" checked="">
                                                                <label class="form-check-label" for="formCheckcolor2">
                                                                    {{  $checkbox2 }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                        @endif
                                                    
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            @endif


                            @if($attribute->input_type === 'select')
                            <div class="col-xl-4 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                        
                                            <div class="flex-grow-1 overflow-hidden">

                                                <h5 class="text-truncate font-size-15">
                                                
                                                    <a href="javascript: void(0);" class="text-dark">{{ $attribute->input_label }}</a>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['attributes.destroy', $attribute->id],'style'=>'display:inline']) !!}
                                                                  <button type="submit" class="btn btn-sm btn-danger fload-end" onclick="return confirm('{{trans('translation.Want_to_delete')}}')"> <i class="fas fa-trash"></i> </button>
                                                                {!! Form::close() !!}
                                                
                                                </h5>
                                                <p class="text-muted mb-4">
                                                
                                                    @if(\App::getLocale()  == "en")
                                                       <select class ="form-control">
                                                           @foreach($attribute->options as $key=>$select)
                                                               <option>
                                                                   {{ $select }}
                                                               </option>
                                                           @endforeach
                                                       </select>
                                                    @else 
                                                 
                                                       
                                                    <select class ="form-control">
                                                        @foreach($attribute->options_ar as $key=>$select2)
                                                            <option>
                                                                {{ $select2 }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                      
                                                  
                                                    @endif
                                                
                                                </p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        @endif






                            @endforeach
                           
                        </div>
                        <!-- end row -->
      
@endsection

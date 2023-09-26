@extends('layouts.backend.master')

@push('title',__('translation.edit_profile'))

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{__('translation.edit_profile')}}</h4>
                    <form action="{{route('admin.update_profile',$admin->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mt-4">
                                    <div>
                                        <label for="name"
                                               class="form-label">{{__('translation.user_name')}}</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="user_name" class="form-control" id="user_name" value="{{$admin->user_name}}" placeholder="{{__('translation.user_name')}}" required>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mt-4">
                                    <div>
                                        <label for="email"
                                               class="form-label">{{__('translation.email')}}</label>
                                        <div class="input-group mb-3">
                                            <input type="email" name="email" class="form-control" id="email" value="{{$admin->email}}" placeholder="{{__('translation.email')}}" required>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mt-4">
                                    <div>
                                        <label for="password"
                                               class="form-label">{{__('translation.password')}}</label>
                                        <div class="input-group mb-3">
                                            <input type="password" name="password" class="form-control" id="password" autocomplete="off"  placeholder="{{__('translation.password')}}" >
                                        </div>
                                    </div>

                                </div>
                            </div>
                           
                            <div class="col-sm-6">
                                    <div class="mt-4">
                                        <label for="taskbudget" class="col-form-label col-lg-3">Photo</label>
                                        <div class="col-lg-6">
                                            <div class="card p-1 border shadow-none">
                                                <div class="position-relative">
                                                    <img class="rounded" width="50" height="50" src="{{asset(ASSET_PATH.'public/uploads/admins/'. $admin->photo)}}">

                                                </div>
                                            </div>
                                            <input type="file" name="photo" id="photo" class="form-control">
                                            @error('photo')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>
                            </div>


                         
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="mt-4">
                                    <div>
                                        <div class="input-group mb-3">
                                            <button type="submit"
                                                    class="btn btn-primary">{{__('translation.edit')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
@endpush




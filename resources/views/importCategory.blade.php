<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>  {{ trans('translation.Import_Export_Categories_Excel_File') }}   </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5 text-center">
        @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
            @php
                Session::forget('error');
            @endphp
        </div>
       @endif
        <h2 class="mb-4">
            {{ trans('translation.Import_Export_Categories_Excel_File') }} 
        </h2>
        <form action="{{ route('import-cat') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="customFile" required>
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <button class="btn btn-primary">    {{ trans('translation.Import') }}  </button>
            <a class="btn btn-success" href="{{ route('export-cats') }}">{{ trans('translation.Export') }}</a>
        </form>
    </div>
</body>

</html>
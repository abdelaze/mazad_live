<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset(ASSET_PATH.'assets/backend/css/payments/bootstrap.min.css') }} ">
  <link rel="stylesheet" href="{{ asset(ASSET_PATH.'assets/backend/css/payments/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset(ASSET_PATH.'assets/backend/css/payments/master.css') }}">
</head>

<body>
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
   
      <div class="modal-header">

        <div class="icon-box">

          <i class="material-icons "><img class="mb-5 p-3 img-fluid" src="{{ asset(ASSET_PATH.'assets/backend/css/payments/22.png') }}" alt=""></i>
        </div>
        <h4 class="modal-title w-100">failed !</h4>
      </div>
      <div class="modal-body">
        <p class="text-center">You Payment Process Failed.</p>
      </div>
     
    </div>
    </div>
</body>

</html>

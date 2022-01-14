<!DOCTYPE html>
<html>

<head>
    <title>Claim Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    @include('components.disclaimer')
    <div class="claim-expire-msg">
        <h2>{{__('messages.expire_claim')}}</h2>
    </div>
  
    <script>
        var baseUrl = "{{ url('/') }}"

    </script>
    <script src="{{ asset('js/claim.js') }}" defer></script>

</body>

</html>

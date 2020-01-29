<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/paper-dashboard.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
       <div class="wrapper ">
          <div class="full-page login-page flex flex-wrap vh-100 align-items-center" data-ps-id="5e3b0485-7bb0-4952-0d9d-2bc475b66c30">
             <!-- End Navbar -->
             <div class="content">
                @yield('content')
             </div>
          </div>
       </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>

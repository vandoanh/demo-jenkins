<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  	<head>
        <title>{!! config('site.general.site_name') !!}</title>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="canonical" href="{!! url()->current() !!}"/>
        <link rel="manifest" href="{!! url('manifest.json') !!}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ url_static('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ url_static('css/app.css') }}">
        {!! config('site.general.ga_code') !!}
    </head>
    <body>
    	<div class="wrap">
            <header class="container bg-point-circle-top"></header>
            @yield('content')
            <footer class="container bg-point-circle-bot"></footer>
    	</div>
    	<!-- /.auth-box -->
        <script type="text/javascript" src="{{ url_static('js/app.js') }}"></script>
        @yield('javascript')
  	</body>
</html>

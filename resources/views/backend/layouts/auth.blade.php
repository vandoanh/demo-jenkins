<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  	<head>
        <title>{!! config('app.name') !!}</title>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{!! csrf_token() !!}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="canonical" href="{!! url()->current() !!}"/>
        <link rel="manifest" href="{!! url('manifest.json') !!}">
        <link rel="shortcut icon" href="{{ asset('favicon_backend.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ url_static('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ url_static('css/app.be.css') }}">
    </head>
    <body class="hold-transition auth-page @yield('class')">
    	<div class="auth-box">
    		<div class="auth-logo">
    			<a href="{{ route('backend.dashboard') }}"><b>Administration</b> Blog</a>
    		</div>
    		<!-- /.auth-logo -->
    		<div class="auth-box-body">
    			@yield('content')
    		</div>
    		<!-- /.auth-box-body -->
    	</div>
    	<!-- /.auth-box -->
        <script type="text/javascript" src="{{ url_static('js/app.be.js') }}"></script>
        @yield('javascript')
  	</body>
</html>

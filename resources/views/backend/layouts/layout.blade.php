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
    <body class="app">
        <div class="main-sidebar">
            @include('backend.layouts.partials.sidebar')
        </div>
        <div class="content-wrapper">
            <div class="main-header">
                @include('backend.layouts.partials.header')
            </div>
            <div class="content">
                <div class="container-fluid pl0 pr0">
                    @include('backend.layouts.partials.breadcrumb')
                    <div class="card bdr-0">
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-footer">
                @include('backend.layouts.partials.footer')
            </div>
        </div>
        <div class="loader-wrapper">
            <div class="loader-overlay">
                <div class="loader loader-xl"></div>
            </div>
        </div>
        <div id="scrolltop">
            <a href="#"></a>
        </div>
        <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content"></div>
            </div>
        </div>
        <script type="text/javascript">
            var CKEDITOR_BASEPATH = "{{ config('app.url') . '/static/js/ckeditor/' }}";
        </script>
        <script type="text/javascript" src="{{ url_static('js/app.be.js') }}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('document').ready(function() {
                backend.init({
                    url: {
                        root: "{{ config('app.url') }}",
                        css: "{{ config('app.url') . '/static/css' }}",
                        images: "{{ config('app.url') . '/static/images' }}",
                        js: "{{ config('app.url') . '/static/js' }}"
                    }
                });

                @if (old('message'))
                    common.showMessage('{{ old('message')[0] }}', 'info');
                @endif

                @if (old('error'))
                    common.showMessage('{{ old('error')[0] }}', 'error');
                @endif
            });
        </script>
        @yield('javascript')
    </body>
</html>

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
        <!-- META FOR FACEBOOK -->
        <meta property="og:site_name" content="{{ config('app.name') }}" />
        <meta property="og:url" itemprop="url" content="{!! url()->current() !!}" />
        <meta property="og:image" itemprop="thumbnailUrl" content="{!! url_static('images/icon_512.png') !!}" />
        <meta property="og:title" itemprop="headline" content="{{ config('site.general.seo.title') }}" />
        <meta property="og:description" itemprop="description" content="{{ config('site.general.seo.description') }}" />
        <!-- END META FOR FACEBOOK -->
        <link rel="canonical" href="{!! url()->current() !!}"/>
        <link rel="manifest" href="{!! url('manifest.json') !!}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ url_static('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ url_static('css/app.css') }}">
    </head>
    <body>
        <div class="wrap">
            @include('frontend.layouts.partials.header')
            <!-- END header -->
            @yield('content')
            @include('frontend.layouts.partials.footer')
            <!-- END footer -->
            @include('frontend.layouts.partials.box_chat')
        </div>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2&appId={{ env('FACEBOOK_CLIENT_ID') }}&autoLogAppEvents=1"></script>
        <script type="text/javascript">
            var CKEDITOR_BASEPATH = "{{ config('app.url') . '/static/js/ckeditor/' }}";
        </script>
        <script type="text/javascript" src="{{ url_static('js/app.js') }}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('document').ready(function() {
                notification.init({
                    subscribe_link: "{!! route('notification.subscribe') !!}",
                    unsubscribe_link: "{!! route('notification.unsubscribe') !!}",
                    vapid_public_key: "{!! config('site.notification.vapid.public_key') !!}"
                });

                if (notification.isSupport()) {
                    notification.subscribe();
                }

                frontend.init({
                    url: {
                        root: "{{ config('app.url') }}",
                        css: "{{ config('app.url') . '/static/css' }}",
                        images: "{{ config('app.url') . '/static/images' }}",
                        js: "{{ config('app.url') . '/static/js' }}"
                    }
                });

                interaction.init({
                    url_get_widget: "{!! route('interaction.widget') !!}",
                    url_get_comment: "{!! route('interaction.comment') !!}",
                    url_post_comment: "{!! route('interaction.comment.post') !!}",
                    url_update_view: "{!! route('interaction.post.view') !!}",
                    url_update_comment_like: "{!! route('interaction.comment.like') !!}",
                    max_time_allow_like: 30
                 });

                chat.init({
                    user_id: '{{ $user_id }}'
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

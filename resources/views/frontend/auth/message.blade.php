@extends('frontend.layouts.auth')

@section('css')
<!-- css link here -->
@stop

@section('content')
<section class="container c-mt-15 c-center">
    <p><a href="{!! route('home') !!}"><img src="{!! url_static('images/logo-eas.png') !!}" alt="logo eas"></a></p>
    <div class="d-flex justify-content-center">
        <div class="card w-50">
            <div class="card-body">
                <h4 class="card-title text-{!! old('message') ? 'info' : 'danger' !!}">
                    <i class="fas fa-{!! old('message') ? 'info-circle' : 'exclamation-triangle' !!}"></i>
                    <span class="ml-2">{!! old('message') ? 'Thông báo' : 'Lỗi' !!}</span>
                </h4>
                <p class="card-text">
                    @if (old('message'))
                        {{ old('message')[0] }}
                    @elseif (old('error'))
                        {{ old('error')[0] }}
                    @endif
                </p>
                <a href="{!! route('home') !!}" class="btn btn-primary btn-sm">Về trang chủ</a>
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function() {
            window.location.href = "{{ route('home') }}";
        }, 3000);
    });
</script>
@stop

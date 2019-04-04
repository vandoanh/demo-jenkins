@extends('frontend.layouts.layout')

@section('content')
<article class="container c-mt-3">
    <h2 class="mb-4">Thông báo</h2>
    @if ($listNotice->isNotEmpty())
        <div class="card-list d-flex justify-content-between flex-wrap">
            @foreach ($listNotice as $notice)
                @if (isset($notice))      
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text font-weight-bold">
                                <a href="{!! route('notice.detail', [$notice->id]) !!}" title="{{ $notice->title }}">{{ $notice->title }}</a>
                            </p>            
                            <p class="card-content">{!! str_limit($notice->content,300) !!}</p>
                            <p class="date">{{ format_date_localize($notice->published_at, 'article') }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="text-center mt-4">{{ $pagination }}</div>
    @endif
</article>
@stop

@section('javascript')
<!-- js link here -->
@stop

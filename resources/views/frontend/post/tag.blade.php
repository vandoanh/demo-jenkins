@extends('frontend.layouts.layout')

@section('content')
<article class="container c-mt-3">
    <h2 class="mb-4">Tag: {{ $tagsInfo->title }}</h2>
    @if ($listPost->isNotEmpty())
        <div class="card-list d-flex justify-content-between flex-wrap">
            @foreach ($listPost as $post)
                <div class="card">
                    <a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">
                        <img class="card-img-top" src="{!! image_url($post->thumbnail_url, '348x261') !!}" alt="{{ $post->title }}">
                    </a>
                    <div class="card-body">
                        <span class="badge badge--small badge--color-yellow badge--arrow badge--arrow-bottom-left">{{ $post->category->title }}</span>
                        <a href="{!! route('post.detail', [$post->code, $post->id]) !!}#box_comment">
                            <span class="badge badge--small badge--color-red badge--arrow badge--arrow-bottom-left" data-type="widget" data-widgettype="{{ config('constants.widget.type.comment') }}" data-widgetid="{{ $post->id }}"></span>
                        </a>
                        <p class="card-text">
                            <a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">{{ $post->title }}</a>
                        </p>
                        <p class="date">{{ format_date_localize($post->published_at, 'article') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">{{ $pagination }}</div>
    @endif
</article>
@stop

@section('javascript')
<!-- js link here -->
@stop

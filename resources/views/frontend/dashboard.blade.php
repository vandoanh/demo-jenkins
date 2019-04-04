@extends('frontend.layouts.layout')

@section('content')
@if ($listBuildTop->isNotEmpty())
    @php
        $firstPost = $listBuildTop->first();
        $listBuildTop->forget(0);
    @endphp
    <article class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="box-cell">
                    <li class="c-pr-1">
                        <a href="{!! route('post.detail', [$firstPost->code, $firstPost->id]) !!}" title="{{ $firstPost->title }}">
                            <div class="image-box">
                                <img src="{!! image_url($firstPost->thumbnail_url, '545x410') !!}" alt="{{ $firstPost->title }}" class="image">
                                <div class="top-head">
                                    <span class="badge badge--small badge--color-yellow badge--arrow badge--arrow-bottom-left">{{ $firstPost->category->title }}</span>
                                    <span class="badge badge--small badge--color-red badge--arrow badge--arrow-bottom-left" data-type="widget" data-widgettype="{{ config('constants.widget.type.comment') }}" data-widgetid="{{ $firstPost->id }}"></span>
                                </div>
                                <div class="overlay full">
                                    <span>{{ $firstPost->title }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        @foreach ($listBuildTop->chunk(2) as $index => $posts)
                            <div class="box-list{!! $index > 0 ? ' c-pt-1' : '' !!}">
                                @foreach ($posts as $i => $post)
                                    <div class="box-item{!! $i % 2 != 0 ? ' c-pr-1' : '' !!}">
                                        <a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">
                                            <div class="image-box">
                                                <img src="{!! image_url($post->thumbnail_url, '268x200') !!}" alt="{{ $post->title }}" class="image-small">
                                                <div class="top-head">
                                                    <span class="badge badge--small badge--color-yellow badge--arrow badge--arrow-bottom-left">{{ $post->category->title }}</span>
                                                    <span class="badge badge--small badge--color-red badge--arrow badge--arrow-bottom-left" data-type="widget" data-widgettype="{{ config('constants.widget.type.comment') }}" data-widgetid="{{ $post->id }}"></span>
                                                </div>
                                                <div class="overlay">{{ $post->title }}</div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </article>
@endif
@if ($listPost->isNotEmpty())
    <article class="container c-mt-3">
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
    </article>
@endif
@stop

@section('javascript')
<!-- js link here -->
@stop

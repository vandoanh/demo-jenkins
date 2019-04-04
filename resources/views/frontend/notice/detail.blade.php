@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-sm-12">
            <!-- article -->
            <article class="article">
                <header class="article__header mb-5">
                    <h1 class="article__title">{{ $noticeInfo->title }}</h1>
                    <span class="date">{{ format_date_localize($noticeInfo->published_at, 'article')}}</span>
                </header>
                <main class="article__content">{!! $noticeInfo->content !!}</main>
            </article>
            <!-- ./article -->
            <!-- related post -->
            <div class="related-posts mt-5">
                <h2 class="heading-secondary mb-5 text-center">Các thông báo khác</h2>
                @if ($listRelatedNotice->isNotEmpty())
                    <div class="card-list d-flex justify-content-between flex-wrap">
                        @foreach ($listRelatedNotice as $notice)
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
                @endif
            </div>
            <!-- ./related post -->
        </div>
    </div>
</section>
@stop

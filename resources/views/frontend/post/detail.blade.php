@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-sm-12">
            <!-- article -->
            <article class="article">
            <header class="article__header mb-3">
                <h1 class="article__title">{{ $postInfo->title }}</h1>
                <span class="date">{{ format_date_localize($postInfo->published_at, 'article')}}</span>
                <span class="tool-tip">{{ $postInfo->category->title }}</span>
            </header>
            <main class="article__content">
                {!! $postInfo->content !!}
                @if (!empty($postInfo->source_name))
                    <div class="text-right font-italic text-decoration">
                        Nguồn: <u><a href="{{ $postInfo->source_link }}" target="_blank">{{ $postInfo->source_name }}</a></u>
                    </div>
                @endif
            </main>
            </article>
            <!-- ./article -->

            <div class="fb-interaction mt-5">
                <div class="fb-like" data-href="{!! $postInfo->share_url !!}" data-layout="button_count" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div>
            </div>

            <!-- comment -->
            @if ($postInfo->show_comment)
                <div id="box_comment" data-params="{{ json_encode(['post_id' => $postInfo->id, 'limit' => config('constants.post.limit.comment')]) }}"></div>
            @endif
            <!-- ./comment -->

            <!-- related post -->
            <div class="related-posts mt-5">
                <h2 class="heading-secondary mb-5 text-center">Các bài viết khác</h2>
                @if ($listRelatedPost->isNotEmpty())
                    <div class="card-list d-flex justify-content-between flex-wrap">
                        @foreach ($listRelatedPost as $post)
                            <div class="card">
                                <a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">
                                    <img class="card-img-top" src="{!! image_url($post->thumbnail_url, '348x261') !!}" alt="{{ $post->title }}">
                                </a>
                                <div class="card-body">
                                    <span class="badge badge--small badge--color-yellow badge--arrow badge--arrow-bottom-left">{{ $post->category->title }}</span>
                                    <a href="{!! route('post.detail', [$post->code, $post->id]) !!}#box_comment">
                                        <span class="badge badge--small badge--color-red badge--arrow badge--arrow-bottom-left" data-type="widget" data-widgettype="{{ config('constants.widget.type.comment') }}" data-widgetid="{{ $post->id }}"></span>
                                    </a>
                                    <p class="card-text"><a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">{{ $post->title }}</a></p>
                                    <p class="date">{{ format_date_localize($post->published_at, 'article') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <!-- ./related post -->
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function(){
        interaction.showComment();
        interaction.updateView('{{ $postInfo->id }}');
    });
</script>
@stop

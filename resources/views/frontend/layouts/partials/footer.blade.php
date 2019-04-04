<section class="c-center mt-5">
    <p><a href="#"><i class="fab fa-instagram font-60"></i></a></p>
    <p class="font-24">
        <span class="text-red">@</span> evolable_asia
    </p>
    @if ($listPost->isNotEmpty())
        <figure class="mt-5">
            @foreach ($listPost as $post)
                <a href="{!! route('post.detail', [$post->code, $post->id]) !!}" title="{{ $post->title }}">
                    <img src="{!! image_url($post->thumbnail_url, '225x225') !!}" alt="{{ $post->title }}">
                </a>
            @endforeach
        </figure>
    @endif
</section>
<footer class="bot-border mt-5">
    <div class="container">
        <a href="#" id="gotop" class="image_box" style="display: block;">
            <img src="{!! url_static('images/top-icon.png') !!}" alt="上へ戻る">
        </a>
        <div class="show_pc">
            <div class="row mt-n3">
                <div class="col">© 2019 Designed &amp; Developed by Evolable.Asia</div>
                <div class="col  c-right">
                    <ul class="register font-1">
                        @foreach ($listCategoryParent as $category)
                            <li>
                                <a href="{!! route('post.category', [$category->code]) !!}">{{ $category->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-n3 show_sp">
            <div class="col c-center">
                <ul class="register font-1">
                    @foreach ($listCategoryParent as $category)
                        <li>
                            <a href="{!! route('post.category', [$category->code]) !!}">{{ $category->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col c-center mt-3">© 2019 Designed &amp; Developed by Evolable.Asia</div>
        </div>
    </div>
</footer>

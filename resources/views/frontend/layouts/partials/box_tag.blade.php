<div class="sidebar-box">
    <h3 class="heading">Tags</h3>
    <ul class="tags">
        @foreach ($listTag as $tag)
            <li><a href="{!! route('post.tag', [$tag->code, $tag->id]) !!}">#{{ $tag->title }}</a></li>
        @endforeach
    </ul>
</div>
<!-- END sidebar-box -->

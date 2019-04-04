<div class="sidebar-box">
    <h3 class="heading">Categories</h3>
    <ul class="categories">
        @foreach ($listCategoryParent as $category)
            <li><a href="{!! route('post.category', [$category->code]) !!}">{{ $category->title }} <span data-type="widget" data-widgettype="{{ config('constants.widget.type.post') }}" data-widgetid="{{ $category->id }}"></span></a></li>
        @endforeach
    </ul>
</div>
<!-- END sidebar-box -->

@foreach ($arrListCategory as $category)
    <div>
        <input type="checkbox" name="category_liston[]" value="{{ $category->id }}"{!! in_array($category->id, $arrListOn) ? ' checked="checked"' : '' !!}>
        <span{!! $category->id == $selected ? ' class="seton"' : '' !!}>{{ $category->title }}</span>
    </div>
    @if ($category->childs->count() > 0)
        @foreach($category->childs as $child)
            <div class="ml15">
                <input type="checkbox" name="category_liston[]" value="{{ $child->id }}" {!! in_array($child->id, $arrListOn) ? ' checked="checked"' : '' !!}>
                <span{!! $child->id == $selected ? ' class="seton"' : '' !!}>{{ $child->title }}</span>
            </div>
        @endforeach
    @endif
@endforeach

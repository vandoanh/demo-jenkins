<div id="notice_content" class="hide">
    <div class="notify">
        <p class="notify__header">Bảng thông báo</p>
        <div class="notify__body">
            @if ($listNotice->isNotEmpty())
                @foreach ($listNotice as $item)
                    <div class="notify__item notify__item--active">
                        <div class="notify__content">
                            <a href="{!! route('notice.detail', [$item->id]) !!}" class="notify__title">{{ $item->title }}</a>
                        </div>
                        <p class="date">{{ format_date_localize($item->published_at, 'article') }}</p>
                    </div>
                @endforeach
            @else
                <div class="notify__item">
                    <div class="notify__content">Không có thông báo mới.</div>
                </div>
            @endif
        </div>
        <p class="notify__footer"><a href="{!! route('notice.index') !!}">Xem tất cả thông báo</a></p>
    </div>
</div>

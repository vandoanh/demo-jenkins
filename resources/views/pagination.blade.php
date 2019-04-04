@if ($arrData->total() > 0)
    <div class="pagination-wrapper">
        @if ($position == 'bottom' && $arrData->total() > $item)
            <div class="text-center">{{ $pagination }}</div>
        @endif
        <div class="d-flex justify-content-between pt05 pb05">
            <div class="text-left pt10">
                Display from {{ $arrData->firstItem() }} to {{ $arrData->lastItem() }} / {{ $arrData->total() }} rows.
            </div>
            <div class="text-right">
                <select class="form-control custom-select" data-pagination="true">
                    @foreach (config('site.general.pagination.list') as $value)
                        <option value="{{ $value }}"{!! $value == $item ? ' selected="selected"' : '' !!}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if ($position == 'top' && $arrData->total() > $item)
            <div class="text-center pb15">{{ $pagination }}</div>
        @endif
    </div>
@endif

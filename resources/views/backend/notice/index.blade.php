@extends('backend.layouts.layout')

@section('content')
<form id="frm-search" name="frm-search" action="{!! route('backend.notice.index') !!}" method="get">
    <div class="card mb20 bg-light">
        <div class="card-body">
            <div class="form-group form-row">
                <div class="col-12 col-md-6 col-lg-2 form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control custom-select" name="status">
                        <option value="">All</option>
                        @foreach (config('constants.status') as $name => $value)
                            <option value="{{ $value }}"{!! $params['status'] == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-4 form-group">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ $params['title'] }}">
                </div>
                <div class="col-12 col-md-6 col-lg-6 form-group">
                    <label class="form-label">Publish Date</label>
                    <div class="clearfix">
                        <div class="input-group date float-left wp48 date_from">
                            <input type="text" class="form-control" name="date_from" value="{{ $params['date_from'] }}" placeholder="From date" />
                            <div class="input-group-append input-group-addon">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="input-group date float-right wp48 date_to">
                            <input type="text" class="form-control" name="date_to" value="{{ $params['date_to'] }}" placeholder="To date" />
                            <div class="input-group-append input-group-addon">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="text-right mb-3">
    <a href="{!! route('backend.notice.create')!!}" role="button" class="btn btn-default"><i class="fas fa-plus"></i> Add new</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <td colspan="7">{!! pagination($listNotice, $pagination, $params['item'], 'top') !!}</td>
            </tr>
            <tr>
                <th class="text-center w10px">ID</th>
                <th>Title</th>
                <th class="text-center w50px">Push to Notification</th>
                <th class="text-center w50px">Push to Chatwork</th>
                <th class="text-center w100px">Published Date</th>
                <th class="text-center w50px">Status</th>
                <th class="text-center w100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listNotice as $notice)
                <tr>
                    <td class="text-center">{{ $notice->id }}</td>
                    <td>{{ $notice->title }}</td>
                    <td class="text-center">{{ $notice->push_notification == config('constants.notice.notification.yes') ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ $notice->push_chatwork == config('constants.notice.chatwork.yes') ? 'Yes' : 'No' }}</td>
                    <td class="text-center">{{ format_date($notice->published_at) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{!! $notice->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $notice->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{!! route('backend.notice.edit', [$notice->id]) !!}" title="Edit notice" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                        <a href="{!! route('backend.notice.delete') !!}" title="Delete notice" data-id="{{ $notice->id }}" data-delete="true" data-reload="true" data-message="Do you want to delete this notice?" class="ml05 mr05"><i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">{!! pagination($listNotice, $pagination, $params['item'], 'bottom') !!}</td>
            </tr>
        </tfoot>
    </table>
</div>
@stop

@section('javascript')
<!-- js link here -->
$('document').ready(function() {
    common.showDatePicker();
});
@stop

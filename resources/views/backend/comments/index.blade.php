@extends('backend.layouts.layout')

@section('content')
<form id="frm-search" name="frm-search" action="{!! route('backend.comment.index') !!}" method="get">
    <div class="card mb20 bg-light">
        <div class="card-body">
            <div class="form-group form-row">
                <div class="col-12 col-md-4 form-group">
                    <label class="form-label">Content</label>
                    <input type="text" class="form-control" name="content" value="{{ $params['content'] }}">
                </div>

                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <td colspan="7">{!! pagination($listComment, $pagination, $params['item'], 'top') !!}</td>
            </tr>
            <tr>
                <th class="text-center w10px">ID</th>
                <th class="text-center w100px">Post</th>
                <th class="text-center w100px">Content</th>
                <th class="text-center w100px">Parent</th>
                <th class="text-center w50px">Author</th>
                <th class="text-center w50px">Status</th>
                <th class="text-center w100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listComment as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td>{{ str_limit($item->post->title,30) }}</td>
                    <td>{{ str_limit($item->content, 100) }}</td>
                    <td>{{ str_limit(($item->parent->content ?? ''), 100) }}</td>
                    <td>{{ $item->user->fullname }}</td>
                    <td class="text-center">
                        <span class="badge badge-{!! $item->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $item->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{!! route('backend.comment.edit', [$item->id])!!}" title="Edit comment" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                        <a href="{!! route('backend.comment.delete') !!}" title="Delete comment" data-id="{{ $item->id }}" data-delete="true" data-reload="true" data-message="Do you want to delete this item?" class="ml05 mr05"><i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">{!! pagination($listComment, $pagination, $params['item'], 'bottom') !!}</td>
            </tr>
        </tfoot>
    </table>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop

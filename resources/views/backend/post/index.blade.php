@extends('backend.layouts.layout')

@section('content')
<form id="frm-search" name="frm-search" action="{!! route('backend.post.index') !!}" method="get">
    <div class="card mb20 bg-light">
        <div class="card-body">
            <div class="form-group form-row">
                <div class="col-12 col-md-6 col-lg-3 form-group">
                    <label class="form-label">Category</label>
                    <select class="form-control" name="category_id">
                        <option value="">All</option>
                        @foreach ($listCategory as $category)
                            <option value="{{ $category->id }}"{!! $params['category_id'] == $category->id ? ' selected="selected"' : '' !!}>{{ $category->title }}</option>
                            @if ($category->childs->count() > 0)
                                @foreach($category->childs as $child)
                                    <option value="{{ $child->id }}"{!! $params['category_id'] == $child->id ? ' selected="selected"' : '' !!}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child->title }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-1 form-group">
                    <label class="form-label">Status</label>
                    <select class="form-control custom-select" name="status">
                        <option value="">All</option>
                        @foreach (config('constants.status') as $name => $value)
                            <option value="{{ $value }}"{!! $params['status'] == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3 form-group">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" value="{{ $params['title'] }}">
                </div>
                <div class="col-12 col-md-6 col-lg-5 form-group">
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
    <a href="{!! route('backend.post.create')!!}" role="button" class="btn btn-default"><i class="fas fa-plus"></i> Add new</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <td colspan="7">{!! pagination($listPost, $pagination, $params['item'], 'top') !!}</td>
            </tr>
            <tr>
                <th class="text-center w10px">ID</th>
                <th class="text-center w100px">Thumbnail</th>
                <th>Title</th>
                <th>Category</th>
                <th class="text-center w100px">Published Date</th>
                <th class="text-center w50px">Status</th>
                <th class="text-center w100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listPost as $post)
                <tr>
                    <td class="text-center">{{ $post->id }}</td>
                    <td class="text-center">
                        <img src="{{ image_url($post->thumbnail_url, '90x67') }}" class="img-thumbnail">
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category->title }}</td>
                    <td class="text-center">{{ format_date($post->published_at) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{!! $post->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $post->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{!! route('backend.post.edit', [$post->id])!!}" title="Edit post" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                        <a href="{!! route('backend.post.delete') !!}" title="Delete post" data-id="{{ $post->id }}" data-delete="true" data-reload="true" data-message="Do you want to delete this post?" class="ml05 mr05"><i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">{!! pagination($listPost, $pagination, $params['item'], 'bottom') !!}</td>
            </tr>
        </tfoot>
    </table>
</div>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $('document').ready(function() {
        common.showDatePicker();
    });
</script>
@stop

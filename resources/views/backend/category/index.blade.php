@extends('backend.layouts.layout')

@section('content')
<div class="text-right mb-3">
    <a href="{!! route('backend.category.create') !!}" role="button" class="btn btn-default"><i class="fas fa-plus"></i> Add new</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <th class="text-center w10px">ID</th>
                <th class="text-center">Title</th>
                <th class="text-center">Code</th>
                <th class="text-center w10px">Posts</th>
                <th class="text-center w100px">Created Date</th>
                <th class="text-center w50px">Status</th>
                <th class="text-center w50px">Show FE</th>
                <th class="text-center w100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->code }}</td>
                    <td class="text-center">{{ $item->posts->count() }}</td>
                    <td class="text-center">{{ format_date($item->created_at) }}</td>
                    <td class="text-center">
                        <span class="badge badge-{!! $item->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $item->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-center">
                        <span>{{ $item->show_fe == \config('constants.post.fe.show') ? 'Yes' : 'No' }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{!! route('backend.category.edit', [$item->id])!!}" title="Edit category" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                        @if ($item->posts->count() === 0 && $item->childs->count() === 0)
                            <a href="{!! route('backend.category.delete') !!}" title="Delete category" data-id="{{ $item->id }}" data-delete="true" data-reload="true" data-message="Do you want to delete this category?" class="ml05 mr05"><i class="fas fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
                @if ($item->childs->count() > 0)
                    @foreach ($item->childs as $child)
                        <tr>
                            <td class="text-center">{{ $child->id }}</td>
                            <td class="pl30">{{ $child->title }}</td>
                            <td>{{ $child->code }}</td>
                            <td class="text-center">{{ $child->posts->count() }}</td>
                            <td class="text-center">{{ format_date($child->created_at) }}</td>
                            <td class="text-center">
                                <span class="badge badge-{!! $child->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $child->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="text-center">
                                <span>{{ $child->show_fe == \config('constants.post.fe.show') ? 'Yes' : 'No' }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{!! route('backend.category.edit', [$child->id])!!}" title="Edit category" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                                @if ($child->posts->count() === 0)
                                    <a href="{!! route('backend.category.delete') !!}" title="Delete category" data-id="{{ $child->id }}" data-delete="true" data-reload="true" data-message="Do you want to delete this category?" class="ml05 mr05"><i class="fas fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop

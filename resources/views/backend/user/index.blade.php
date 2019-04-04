@extends('backend.layouts.layout')

@section('content')
<div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-list"></i> Users</h3>
</div>
<hr>
<form id="frm-search" name="frm-search" action="{!! route('backend.user.index') !!}" method="get">
    <div class="card mb20 bg-light">
        <div class="card-body">
            <div class="form-group form-row">
                <div class="col-12 col-md-6 col-lg-3 form-group">
                    <label class="form-label">Fullname</label>
                    <input type="text" class="form-control" value="{!! old('fullname', $params['fullname']) !!}" name="fullname" id="fullname"  placeholder="Search fullname...">
                </div>
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="text-right mb-3">
    <a href="{!! route('backend.user.create')!!}" role="button" class="btn btn-default"><i class="fas fa-plus"></i> Add new</a>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-light">
            <tr>
                <td colspan="9">{!! pagination($users, $pagination, $params['item'], 'top') !!}</td>
            </tr>
            <tr>
                <th class="text-center w10px">ID</th>
                <th class="text-center w100px">Avatar</th>
                <th>Fullname</th>
                <th>Email</th>
                <th class="text-center w100px">Gender</th>
                <th class="text-center w100px">Birthday</th>
                <th class="text-center w100px">User Type</th>
                <th class="text-center w50px">Status</th>
                <th class="text-center w100px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td class="text-center">
                        <img src="{{ image_url($user->avatar, '200x168') }}" class="img-thumbnail">
                    </td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">{{ $user->gender == config('constants.user.gender.male') ? 'Male' : ($user->gender == config('constants.user.gender.female') ? 'Female' : '') }}</td>
                    <td class="text-center">{{ format_date($user->birthday, 'd/m/Y') }}</td>
                    <td class="text-center">{{ $user->user_type == \config('constants.user.type.admin') ? 'Admin' : 'Member' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{!! $user->status == config('constants.status.active') ? 'info' : 'warning' !!} p05">{{ $user->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{!! route('backend.user.detail', [$user->id]) !!}" title="View Detail User" class="ml05 mr05"><i class="fas fa-eye"></i>
                        </a>
                        <a href="{!! route('backend.user.edit', [$user->id])!!}" title="Edit User" class="ml05 mr05"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">{!! pagination($users, $pagination, $params['item'], 'bottom') !!}</td>
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

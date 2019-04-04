@extends('backend.layouts.layout')

@section('content')
<div class="form-row justify-content-center">
    <div class="col-12 col-md-7 col-lg-7 col-xl-5">
        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th> Email </th>
                        <td class="text-right"> {{ $userInfo->email }} </td>
                    </tr>
                    <tr>
                        <th> Fullname </th>
                        <td class="text-right"> {{ $userInfo->fullname }} </td>
                    </tr>
                    <tr>
                        <th> Gender </th>
                        <td class="text-right"> {{ $userInfo->gender == config('constants.user.gender.male') ? 'Male' : ($userInfo->gender == config('constants.user.gender.female') ? 'Female' : '') }}</td>
                    </tr>
                    <tr>
                        <th> Avatar </th>
                        <td class="text-right"> <img src="{{ image_url($userInfo->avatar) }}" width="100"> </td>
                    </tr>
                    <tr>
                        <th> Birthday </th>
                        <td class="text-right">{{ format_date($userInfo->birthday, 'd/m/Y') }} </td>
                    </tr>
                    <tr>
                        <th> User type </th>
                        <td class="text-right"> {!! $userInfo->user_type == config('constants.user.type.admin') ? 'Admin' : 'Member' !!} </td>
                    </tr>
                    <tr>
                        <th>
                            <a href="{!! route('backend.user.edit.profile') !!}" role="button" class="btn btn-success btn-xs" title="Edit profile"><i class="far fa-edit"></i> Edit profile</a>
                        </th>
                        <td class="text-right">
                            <a href="{!! route('backend.user.change.password') !!}" role="button" class="btn btn-primary btn-xs" title="Change Password"><i class="far fa-edit"></i> Change Password</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('javascript')
<!-- js link here -->
@stop

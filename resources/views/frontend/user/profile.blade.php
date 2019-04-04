@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
            @include('frontend.user.partials.menu')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th> Email </th>
                            <td> {{ $userInfo->email }} </td>
                        </tr>
                        <tr>
                            <th> Họ & tên </th>
                            <td> {{ $userInfo->fullname }} </td>
                        </tr>
                        <tr>
                            <th> Giới tính </th>
                            <td> {{ $userInfo->gender == config('constants.user.gender.male') ? 'Nam' : ($userInfo->gender == config('constants.user.gender.female')
                                ? 'Nữ' : '') }}</td>
                        </tr>
                        <tr>
                            <th> Hình đại diện </th>
                            <td> <img src="{{ image_url($userInfo->avatar) }}" width="100"> </td>
                        </tr>
                        <tr>
                            <th> Ngày sinh </th>
                            <td>{{ format_date($userInfo->birthday, 'd/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 col-xl-6">
                    <a href="{!! route('user.edit.profile')!!}" title="Sửa thông tin cá nhân">
                        <p>
                            <button class="btn btn-dark"><i class="far fa-edit"></i> Sửa thông tin cá nhân</button>
                        </p>
                    </a>
                </div>
                <div class="col-12 col-lg-6 col-xl-6">
                    <a href="{!! route('user.change.password')!!}" title="Thay đổi mật khẩu">
                        <p>
                            <button class="btn btn-dark"><i class="far fa-edit"></i> Thay đổi mật khẩu</button>
                        </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
@stop

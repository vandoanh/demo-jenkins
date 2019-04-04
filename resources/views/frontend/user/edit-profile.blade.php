@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
            @include('frontend.user.partials.menu')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <form action="{!! route('user.update.profile') !!}" method="post" id="frmUpdate" name="frmUpdate">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="col-12">
                        <label class="form-label form-required" for="fullname">Họ & tên</label>
                    </div>
                    <div class="col-12">
                        <input type="text" class="form-control" placeholder="Họ & tên"  name="fullname" id="fullname" value="{!! old('fullname', $userInfo->fullname) !!}" />
                        <span class="icon top-4"><i class="fa fa-user font-20"></i></span>
                        {!! show_error($errors, 'fullname') !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-7">
                        <label class="form-label" for="birthday">Ngày sinh</label>
                        <div class="input-group date datepicker padding-none">
                            <input type="text" class="form-control datepicker" placeholder="Birthday" name="birthday" id="birthday" value="{!! old('birthday', format_date($userInfo->birthday, 'd/m/Y')) !!}"/>
                            <span class="icon"><i class="fa fa-birthday-cake font-16"></i></span>
                        </div>
                        {!! show_error($errors, 'birthday') !!}
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label" for="gender">Giới tính</label>
                        <select class="classic" name="gender" id="gender">
                            @foreach (config('constants.user.gender') as $name => $value)
                                <option value="{{ $value }}" {!! old('gender', $userInfo->gender) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                        {!! show_error($errors, 'gender') !!}
                    </div>
                </div>
                <div class="form-group" id="frmUserAvatar">
                    <label class="form-label">Hình đại diện</label>
                    <div class="form-row" id="uploadInfo">
                        <div class="col-6 form-group">
                            <input type="hidden" id="avatar" name="avatar" value="{{ old('avatar', $userInfo->avatar) }}">
                            <div id="fileUploader">Tải hình ảnh</div>
                        </div>
                        <div class="col-6 form-group text-right">
                            <img src="{{ image_url(old('avatar', $userInfo->avatar), '90x90') }}" data-old="{{ $userInfo->avatar }}" data-url-old="{{ image_url($userInfo->avatar, '90x90') }}" alt="{{ old('avatar', $userInfo->avatar) }}" class="w90px" />
                        </div>
                    </div>
                    {!! show_error($errors, 'avatar') !!}
                </div>
                <p class="text-center">
                    <button type="submit" name="change_profile" class="btn btn-danger"><i class="fas fa-save"></i> Thay đổi thông tin</button>
                </p>
            </form>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function() {
        common.uploadAvatar({
            url: "{!! config('site.media.url.upload.form') . '/image' !!}",
            uploadPanel: '#uploadInfo',
            maxFileAllowed: 1,
            allowedTypes: "{!! implode(',', config('site.media.type.image')) !!}", //seperate with ','
            maxFileSize: "{!! config('site.media.size.image') !!}", //in byte
            mediaUrl: "{!! image_url('tmp.jpg', '90x90') !!}",
            dragDropStr: 'Kéo và thả hình vào đây.'
        });
    });
</script>
@stop

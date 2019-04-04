@extends('backend.layouts.layout')

@section('content')
<form id="formPost" action="{!! route('backend.user.update.profile') !!}" method="POST" novalidate id="frmUpdate" name="frmUpdate">
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-md-7 col-lg-7 col-xl-5">
            <div class="form-group">
                <label for="fullname" class="form-label form-required">Email</label>
                <input type="text" class="form-control" placeholder="Please enter your fullname" name="fullname" id="fullname" value="{!! old('fullname', $userInfo->fullname) !!}" />
                {!! show_error($errors, 'fullname') !!}
            </div>
            <div class="form-row">
                <div class="col-12 col-sm-6 form-group">
                    <label for="birthday" class="form-label">Birthday</label>
                    <div class="input-group date datepicker">
                        <input type="text" class="form-control" placeholder="Birthday" name="birthday" id="birthday" value="{!! old('birthday', format_date($userInfo->birthday, 'd/m/Y')) !!}"/>
                        <div class="input-group-append input-group-addon">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    {!! show_error($errors, 'birthday') !!}
                </div>
                <div class="col-12 col-sm-6 form-group">
                    <label class="form-label" for="gender">Gender</label>
                    <select class="form-control custom-select" name="gender" id="gender">
                        @foreach (config('constants.user.gender') as $name => $value)
                            <option value="{{ $value }}" {!! old('gender', $userInfo->gender) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'gender') !!}
                </div>
            </div>
            <div class="form-group" id="frmUserAvatar">
                <label class="form-label">Avatar</label>
                <div class="form-row" id="uploadInfo">
                    <div class="col-6 form-group">
                        <input type="hidden" id="avatar" name="avatar" value="{{ old('avatar', $userInfo->avatar) }}">
                        <div id="fileUploader">Upload</div>
                    </div>
                    <div class="col-6 form-group text-right">
                        <img src="{{ image_url(old('avatar', $userInfo->avatar), '90x90') }}" data-old="{{ $userInfo->avatar }}" data-url-old="{{ image_url($userInfo->avatar, '90x90') }}" alt="{{ old('avatar', $userInfo->avatar) }}" class="w90px" />
                    </div>
                </div>
                {!! show_error($errors, 'avatar') !!}
            </div>
            <div class="form-row form-group mb0">
                <div class="col-6">
                    <a href="{!! route('backend.user.profile') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
                </div>
                <div class="col-6 text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </div>

        </div>

    </div>
</form>
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
            mediaUrl: "{!! image_url('tmp.jpg', '90x90') !!}"
        });
    });
</script>
@stop

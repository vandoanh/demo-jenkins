@extends('backend.layouts.layout')

@section('content')
<form id="formNotice" action="{!! route('backend.notice.store') !!}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="form-row">
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <label for="title" class="form-label form-required">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                    {!! show_error($errors, 'title') !!}
                </div>
                <div class="form-group">
                    <label for="content" class="form-label form-required">Content</label>
                    <textarea class="form-control" id="content" name="content" placeholder="Content" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px']) }}">{{ old('content') }}</textarea>
                    {!! show_error($errors, 'content') !!}
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <label for="content_chatwork" class="form-label">Content for Chatwork</label>
                    <textarea class="form-control h350px" id="content_chatwork" name="content_chatwork" placeholder="Content for Chatwork">{{ old('description') }}</textarea>
                    {!! show_error($errors, 'content_chatwork') !!}
                </div>
                <div class="form-row">
                    <div class="col-12 col-sm-6 col-md-3 form-group">
                        <label for="push_notification" class="form-label">Push to Notification</label>
                        <select class="form-control" id="push_notification" name="push_notification">
                            @foreach (config('constants.notice.notification') as $name => $value)
                                <option value="{{ $value }}"{!! old('push_notification', config('constants.notice.notification.no')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                        {!! show_error($errors, 'push_notification') !!}
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 form-group">
                        <label for="push_chatwork" class="form-label">Push to Chatwork</label>
                        <select class="form-control" id="push_chatwork" name="push_chatwork">
                            @foreach (config('constants.notice.chatwork') as $name => $value)
                                <option value="{{ $value }}"{!! old('push_chatwork', config('constants.notice.chatwork.no')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                        {!! show_error($errors, 'push_chatwork') !!}
                    </div>
                    <div class="col-12 col-sm-6 col-md-2 form-group">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            @foreach (config('constants.status') as $name => $value)
                                <option value="{{ $value }}"{!! old('status', config('constants.status.active')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                            @endforeach
                        </select>
                        {!! show_error($errors, 'status') !!}
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 form-group">
                        <label for="published_at" class="form-label">Publish date</label>
                        <div class="input-group date datetime">
                            <input type="text" class="form-control" id="published_at" name="published_at" value="{{ old('published_at') }}" placeholder="Publish date" />
                            <div class="input-group-append input-group-addon">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                        </div>
                        {!! show_error($errors, 'published_at') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 form-group mt15 mb0">
            <div class="form-row">
                <div class="col-6">
                    <a href="{!! route('backend.notice.index') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
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
    $('document').ready(function() {
        common.showDateTimePicker();
    });
</script>
@stop

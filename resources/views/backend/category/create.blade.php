@extends('backend.layouts.layout')

@section('content')
<form id="formCategory" action="{!! route('backend.category.store') !!}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-md-7 col-lg-7 col-xl-5">
            <div class="form-row">
                <div class="col-12 form-group">
                    <label for="title" class="form-label form-required">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" data-code="true" data-for="#code" data-link="{!! route('backend.create-code') !!}">
                    {!! show_error($errors, 'title') !!}
                </div>
                <div class="col-12 form-group">
                    <label for="code" class="form-label form-required">Code</label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}">
                    {!! show_error($errors, 'code') !!}
                </div>
                <div class="col-12 form-group">
                    <label for="parent_id" class="form-label">Parent</label>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="0">--none--</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}"{!! old('parent_id') == $parent->id ? ' selected="selected"' : '' !!}>{{ $parent->title }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'parent_id') !!}
                </div>
                <div class="col-12 col-sm-4 form-group">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control custom-select" id="status" name="status">
                        @foreach (config('constants.status') as $name => $value)
                            <option value="{{ $value }}" {!! old('status', config('constants.status.active')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'status') !!}
                </div>
                <div class="col-12 col-sm-4 form-group">
                    <label for="show_fe" class="form-label">Show FE</label>
                    <select class="form-control custom-select" id="show_fe" name="show_fe">
                        @foreach (config('constants.post.fe') as $name => $value)
                            <option value="{{ $value }}" {!! old('show_fe', config('constants.post.fe.show')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'show_fe') !!}
                </div>
                <div class="col-12 col-sm-4 form-group">
                    <label for="display_order" class="form-label">Display Order</label>
                    <input type="text" class="form-control" id="display_order" name="display_order" value="{{ old('display_order') }}">
                    {!! show_error($errors, 'display_order') !!}
                </div>
                <div class="col-12 form-group mt15 mb0">
                    <div class="form-row">
                        <div class="col-6">
                            <a href="{!! route('backend.category.index') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
                        </div>
                        <div class="col-6 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function()
        common.createCode();
    });
</script>
@stop

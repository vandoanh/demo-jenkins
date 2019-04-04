@extends('backend.layouts.layout')

@section('content')
<form id="formComment" action="{!! route('backend.comment.update', [$data->id]) !!}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-lg-7 col-md-7">

            <div class="form-group">
                <label for="content" class="form-label form-required">Content</label>
                <textarea class="form-control" id="content" name="content" placeholder="Content" rows="10">{{ old('content', $data->content) }}</textarea>
                {!! show_error($errors, 'content') !!}
            </div>
            <div class="form-group form-row">
                <div class="col-3 form-group">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        @foreach (config('constants.status') as $name => $value)
                            <option value="{{ $value }}" {!! old('status', $data->status) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                        @endforeach
                    </select>
                    {!! show_error($errors, 'status') !!}
                </div>

            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-6">
                        <a href="{!! route('backend.comment.index') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
                    </div>
                    <div class="col-6 text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
@stop

@section('javascript')
<!-- js link here -->
@stop

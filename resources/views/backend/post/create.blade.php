@extends('backend.layouts.layout')

@section('content')
<form id="formPost" action="{!! route('backend.post.store') !!}" method="POST" novalidate>
    {{ csrf_field() }}
    <div class="form-row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="title" class="form-label form-required">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Title" data-code="true" data-for="#code" data-link="{!! route('backend.create-code') !!}" aria-required="true">
                {!! show_error($errors, 'title') !!}
            </div>
            <div class="form-group">
                <label for="code" class="form-label form-required">Code</label>
                <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="Code">
                {!! show_error($errors, 'code') !!}
            </div>
            <div class="form-group">
                <label for="description" class="form-label form-required">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description">{{ old('description') }}</textarea>
                {!! show_error($errors, 'description') !!}
            </div>
            <div class="form-group">
                <label for="content" class="form-label form-required">Content</label>
                <textarea class="form-control" id="content" name="content" placeholder="Content" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px']) }}">{{ old('content') }}</textarea>
                {!! show_error($errors, 'content') !!}
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="form-row">
                <div class="col-12 col-md-6 form-group">
                    <label for="category_id" class="form-label form-required">Category</label>
                    <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}">
                    <div data-for="category_id" class="list-folder bdr-c3 p05 r05">
                        @include('backend.post.partials.list_category', ['arrListCategory' => $listCategory, 'arrListOn' => old('category_liston', []), 'selected' => old('category_id')])
                    </div>
                    {!! show_error($errors, 'category_id') !!}
                </div>
                <div class="col-12 col-md-6 form-group">
                    <div class="form-group" id="frmThumbnail">
                        <label class="form-label">Thumbnail</label>
                        <div class="form-row" id="uploadInfo">
                            <div class="col-8 form-group">
                                <input type="hidden" id="thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url', 'no-image.jpg') }}">
                                <div id="fileUploader">Upload</div>
                            </div>
                            <div class="col-4 form-group text-right">
                                <img src="{{ image_url(old('thumbnail_url', 'no-image.jpg'), '90x67') }}" data-old="no-image.jpg" data-url-old="{{ image_url('no-image.jpg', '90x67') }}" alt="{{ old('thumbnail_url', 'no-image.jpg') }}" class="w90px" />
                            </div>
                        </div>
                        {!! show_error($errors, 'thumbnail_url') !!}
                    </div>
                    <div class="form-group form-row">
                        <div class="col-6 form-group">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                @foreach (config('constants.status') as $name => $value)
                                    <option value="{{ $value }}" {!! old('status', config('constants.status.active')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                                @endforeach
                            </select>
                            {!! show_error($errors, 'status') !!}
                        </div>
                        <div class="col-6 form-group">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-control" id="priority" name="priority">
                                @foreach (config('constants.post.priority') as $name => $value)
                                    <option value="{{ $value }}" {!! old('priority', config('constants.post.priority.normal')) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                                @endforeach
                            </select>
                            {!! show_error($errors, 'priority') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-control" data-width="100%" data-multiselect="true" data-placeholder="Choose tag" data-tags="true" data-fields="name|name" id="tags" name="tags[]" multiple="multiple">
                            @foreach (old('tags', []) as $tag)
                                <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="card">
                    <div class="card-header bg-light">
                        <h3 class="card-title mb0">SEO</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="seo_title" class="form-label">Web title</label>
                            <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title') }}" placeholder="Web title">
                        </div>
                        <div class="form-group">
                            <label for="seo_keywords" class="form-label">Keywords</label>
                            <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords') }}" placeholder="Keywords">
                        </div>
                        <div class="form-group">
                            <label for="seo_description" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" id="seo_description" name="seo_description" placeholder="Description">{{ old('seo_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 form-group mt15 mb0">
            <div class="form-row">
                <div class="col-6">
                    <a href="{!! route('backend.post.index') !!}" role="button" class="btn btn-default"><i class="fas fa-angle-double-left"></i> Back</a>
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
        $('.list-folder').on('click', 'input:checkbox', function() {
            common.listOnCategory($(this));
        });
        $('.list-folder').on('click', 'span', function() {
            common.setOnCategory($(this));
        });

        common.createCode();

        common.multiSelect();

        common.uploadThumbnail({
            url: "{!! config('site.media.url.upload.form') . '/image' !!}",
            uploadPanel: '#uploadInfo',
            maxFileAllowed: 1,
            allowedTypes: "{!! implode(',', config('site.media.type.image')) !!}", //seperate with ','
            maxFileSize: "{!! config('site.media.size.image') !!}", //in byte
            mediaUrl: "{!! image_url('tmp.jpg', '90x67') !!}"
        });
    });
</script>
@stop

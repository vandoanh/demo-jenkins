@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
            @include('frontend.user.partials.menu')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <form id="formPost" action="{!! route('user.post.update', [$postInfo->id]) !!}" method="POST" novalidate>
                {{ csrf_field() }}
                <div class="form-row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="form-group">
                            <label class="form-label form-required">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $postInfo->title) }}" placeholder="Tiêu đề" data-code="true" data-for="#code" data-link="{!! route('backend.create-code') !!}" aria-required="true">
                            {!! show_error($errors, 'title') !!}
                        </div>
                        <div class="form-group">
                            <label class="form-label form-required">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $postInfo->code) }}" placeholder="Code">
                            {!! show_error($errors, 'code') !!}
                        </div>
                        <div class="form-group">
                            <label class="form-label form-required">Tóm tắt</label>
                            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Tóm tắt">{{ old('description', $postInfo->description) }}</textarea>
                            {!! show_error($errors, 'description') !!}
                        </div>
                        <div class="form-group">
                            <label class="form-label form-required">Nội dung</label>
                            <textarea class="form-control" id="content" name="content" placeholder="Nội dung" data-editor="{{ json_encode(['width' => '99%', 'height' => '425px']) }}">{{ old('content', $postInfo->content) }}</textarea>
                            {!! show_error($errors, 'content') !!}
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="form-row">
                            <div class="col-12 form-group">
                                <label for="category_id" class="form-label form-required">Danh Mục</label>
                                <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id', $postInfo->category_id) }}">
                                <div data-for="category_id" class="list-folder p-3 border rounded">
                                    @include('frontend.user.post.partials.list_category', ['arrListCategory' => $listCategory, 'arrListOn' => old('category_liston', $postInfo->category_liston), 'selected' => old('category_id', $postInfo->category_id)])
                                </div>
                                {!! show_error($errors, 'category_id') !!}
                            </div>
                            <div class="col-12 form-group">
                                <div class="form-group" id="frmThumbnail">
                                    <label class="form-label">Thumbnail</label>
                                    <div class="form-row" id="uploadInfo">
                                        <div class="col-9 form-group">
                                            <input type="hidden" id="thumbnail_url" name="thumbnail_url" value="{{ old('thumbnail_url', $postInfo->thumbnail_url) }}">
                                            <div id="fileUploader">Tải hình ảnh</div>
                                        </div>
                                        <div class="col-3 form-group text-right">
                                            <img src="{{ image_url(old('thumbnail_url', $postInfo->thumbnail_url), '90x67') }}" data-old="{{ $postInfo->thumbnail_url }}" data-url-old="{{ image_url($postInfo->thumbnail_url, '90x67') }}" alt="{{ old('thumbnail_url', $postInfo->thumbnail_url) }}" class="w90px" />
                                        </div>
                                    </div>
                                    {!! show_error($errors, 'thumbnail_url') !!}
                                </div>
                                <div class="form-row">
                                    <div class="col-6 col-lg-12 form-group">
                                        <label class="form-label">Trạng thái</label>
                                        <select class="classic" id="status" name="status">
                                            @foreach (config('constants.status') as $name => $value)
                                                <option value="{{ $value }}" {!! old('status', $postInfo->status) == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                                            @endforeach
                                        </select>
                                        {!! show_error($errors, 'status') !!}
                                    </div>
                                    <div class="col-6 col-lg-12 form-group">
                                        <select class="classic" data-width="100%" data-multiselect="true" data-placeholder="Gắn Thẻ" data-tags="true" data-fields="name|name" id="tags" name="tags[]" multiple="multiple">
                                            @foreach (old('tags', $postInfo->tags) as $tag)
                                                <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 form-group mt-3 mb0">
                        <div class="form-row">
                            <div class="col-6">
                                <a href="{!! route('user.post') !!}" role="button" class="btn btn-dark"><i class="fas fa-angle-double-left"></i> Trở về</a>
                            </div>
                            <div class="col-6 text-right">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Lưu</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
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
            mediaUrl: "{!! image_url('tmp.jpg', '90x67') !!}",
            dragDropStr: 'Kéo và thả hình vào đây.'
        });
    });
</script>
@stop

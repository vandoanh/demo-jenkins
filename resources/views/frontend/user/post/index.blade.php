@extends('frontend.layouts.layout')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12 col-lg-4 col-xl-3">
            @include('frontend.user.partials.menu')
        </div>
        <div class="col-12 col-lg-8 col-xl-9">
            <form id="frm-search" name="frm-search" action="{!! route('user.post') !!}" method="get">
                <div class="card mb20 bg-light">
                    <div class="card-body">
                        <div class="form-group form-row">
                            <div class="col-12 col-md-6 col-lg-4 form-group">
                                <select class="classic" name="category_id">
                                    <option value="">Danh Mục</option>
                                    @foreach ($listCategory as $category)
                                        <option value="{{ $category->id }}"{!! $params['category_id'] == $category->id ? ' selected="selected"' : '' !!}>{{ $category->title }}</option>
                                        @if ($category->childs->count() > 0)
                                            @foreach($category->childs as $child)
                                                <option value="{{ $child->id }}"{!! $params['category_id'] == $child->id ? ' selected="selected"' : '' !!}>&nbsp;&nbsp;&nbsp;&nbsp;{{ $child->title }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3 form-group">
                                <select class="classic" name="status">
                                    <option value="">Trạng Thái</option>
                                    @foreach (config('constants.status') as $name => $value)
                                        <option value="{{ $value }}"{!! $params['status'] == $value ? ' selected="selected"' : '' !!}>{{ ucfirst($name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-5 form-group">
                                <input type="text" class="form-control" name="title" value="{{ $params['title'] }}" placeholder="Tiêu Đề">
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 form-group">
                                <div class="input-group date float-left wp48 date_from">
                                    <input type="text" class="form-control" name="date_from" value="{{ $params['date_from'] }}" placeholder="Từ Ngày" />
                                    <div class="input-group-append input-group-addon">
                                        <span class="icon"><i class="fas fa-calendar font-20"></i></span>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 form-group">
                                <div class="input-group date float-right wp48 date_to">
                                    <input type="text" class="form-control" name="date_to" value="{{ $params['date_to'] }}" placeholder="Đến Ngày" />
                                    <div class="input-group-append input-group-addon">
                                        <span class="icon"><i class="fas fa-calendar font-20"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-right mt-3">
                                <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Tìm Kiếm </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="text-right mb-4 mt-5 mr-3">
                <a href="{!! route('user.post.create')!!}" role="button" class="btn btn-dark"><i class="fas fa-plus"></i> Thêm Mới</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        @if ($listPost->total() > $params['item'])
                            <tr>
                                <td colspan="6" class="pt-4 pl-4">{{ $pagination }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="text-center w100px font-weight-normal p-4">Thumbnail</th>
                            <th class="font-weight-normal text-nowrap p-4">Tiêu Đề</th>
                            <th class="font-weight-normal text-nowrap p-4">Danh Mục</th>
                            <th class="text-center text-nowrap w100px font-weight-normal p-4">Phát hành</th>
                            <th class="text-center text-nowrap font-weight-normal p-4">Trạng Thái</th>
                            <th class="text-center w50px font-weight-normal p-4">Sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listPost as $post)
                            <tr>
                                <td class="text-center p-3">
                                    <img src="{{ image_url($post->thumbnail_url, '90x67') }}" class="img-thumbnail w90px">
                                </td>
                                <td class="p-3">{{ $post->title }}</td>
                                <td class="p-3">{{ $post->category->title }}</td>
                                <td class="text-center p-3">{{ format_date($post->published_at) }}</td>
                                <td class="text-center p-3">
                                    <span class="badge badge-{!! $post->status == config('constants.status.active') ? 'danger' : 'warning' !!} p05">{{ $post->status == config('constants.status.active') ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td class="text-center p-3">
                                    <a href="{!! route('user.post.edit', [$post->id])!!}" title="Chỉnh sửa" class="ml05 mr05"><i class="fas fa-edit" style="color:#dc3545"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($listPost->total() > $params['item'])
                        <tfoot>
                            <tr>
                                <td colspan="6" class="pt-4 pl-4">{{ $pagination }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</section>
@stop

@section('javascript')
<!-- js link here -->
<script type="text/javascript">
    $('document').ready(function() {
        common.showDatePicker();
    });
</script>
@stop

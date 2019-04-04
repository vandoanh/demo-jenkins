<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Models\Notice;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $item = config('constants.post.limit.default');
        $page = $request->page ?? 1;

        $listNotice = Notice::instance()->getListNotice([
            'item' => $item,
            'page' => $page,
        ]);
        if ($listNotice->total() > 0) {
            $maxPage = ceil($listNotice->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('notice.index', ['page' => $maxPage]));
            }
        }
        $pagination = $listNotice->withPath(route('notice.index'))->onEachSide(config('site.general.pagination.each_side'))->links();

        return view('frontend.notice.index')->with([
            'listNotice' => $listNotice,
            'pagination' => $pagination,
        ]);
    }

    public function detail(Request $request, $id)
    {
        $noticeInfo = Notice::instance()->getDetailNotice($id);

        if (!$noticeInfo) {
            abort(404);
        }

        Notice::instance()->setExclude($noticeInfo->id);

        //get related notice
        $listRelatedNotice = Notice::instance()->getListNotice([
            'limit' => config('constants.post.limit.related'),
        ]);

        return view('frontend.notice.detail')->with([
            'noticeInfo' => $noticeInfo,
            'listRelatedNotice' => $listRelatedNotice,
        ]);
    }
}

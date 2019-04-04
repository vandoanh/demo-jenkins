<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Models\Post;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function dashboard(Request $request)
    {
        $modelPost = Post::instance();

        $listBuildTop = $modelPost->getBuildTop(config('constants.post.limit.build_top'));
        if ($listBuildTop->isNotEmpty()) {
            $modelPost->setExclude($listBuildTop->pluck('id')->toArray());
        }

        $item = config('constants.post.limit.default');
        $page = $request->page ?? 1;

        $listPost = $modelPost->getAllPost([
            'item' => $item,
            'page' => $page,
        ]);
        if ($listPost->total() > 0) {
            $maxPage = ceil($listPost->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('group.index', ['page' => $maxPage]));
            }
        }
        $pagination = $listPost->onEachSide(config('site.general.pagination.each_side'))->links();
        return view('frontend.dashboard')->with([
            'listBuildTop' => $listBuildTop,
            'listPost' => $listPost,
            'pagination' => $pagination,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Models\Category;
use App\Library\Models\Post;
use App\Library\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function category(Request $request, $code)
    {
        $categoryInfo = Category::instance()->getDetailCategoryByCode($code);

        if (!$categoryInfo) {
            abort(404);
        }

        $item = config('constants.post.limit.default');
        $page = $request->page ?? 1;

        $listPost = Post::instance()->getListPostByCateListOn([
            'category_id' => $categoryInfo->id,
            'item' => $item,
            'page' => $page,
        ]);
        if ($listPost->total() > 0) {
            $maxPage = ceil($listPost->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('post.category', ['page' => $maxPage]));
            }
        }
        $pagination = $listPost->withPath(route('post.category', [$categoryInfo->code]))->onEachSide(config('site.general.pagination.each_side'))->links();

        return view('frontend.post.category')->with([
            'listPost' => $listPost,
            'categoryInfo' => $categoryInfo,
            'pagination' => $pagination,
        ]);
    }

    public function search(Request $request)
    {
        $title = $request->key ?? '';
        $listPost = collect([]);
        $pagination = null;

        if (!empty($title)) {
            $item = config('constants.post.limit.default');
            $page = $request->page ?? 1;

            $listPost = Post::instance()->searchPosts([
                'title' => $title,
                'item' => $item,
                'page' => $page,
            ]);

            if ($listPost->total() > 0) {
                $maxPage = ceil($listPost->total() / $item);
                if ($maxPage < $page) {
                    return redirect(route('post.category', ['page' => $maxPage]));
                }
            }
            $pagination = $listPost->withPath(route('post.search'))->onEachSide(config('site.general.pagination.each_side'))->appends(['key' => $title, 'page' => $page])->links();
        }

        return view('frontend.post.search')->with([
            'listPost' => $listPost,
            'key' => $title,
            'pagination' => $pagination,
        ]);
    }

    public function tag(Request $request, $code, $id)
    {
        $tagsInfo = Tag::instance()->getDetailTag($id);

        if (!$tagsInfo) {
            abort(404);
        }

        if ($tagsInfo->code !== $code) {
            return redirect(route('post.detail', [$tagsInfo->code, $tagsInfo->id]), 302);
        }

        $item = config('constants.post.limit.default');
        $page = $request->page ?? 1;

        $listPost = Post::instance()->getListPostByTag([
            'tag_title' => $tagsInfo->title,
            'item' => $item,
            'page' => $page,
        ]);
        if ($listPost->total() > 0) {
            $maxPage = ceil($listPost->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('post.tag', ['page' => $maxPage]));
            }
        }
        $pagination = $listPost->withPath(route('post.tag', [$tagsInfo->code, $tagsInfo->id]))->onEachSide(config('site.general.pagination.each_side'))->links();

        return view('frontend.post.tag')->with([
            'listPost' => $listPost,
            'tagsInfo' => $tagsInfo,
            'pagination' => $pagination,
        ]);
    }

    public function detail(Request $request, $code, $id)
    {
        $postInfo = Post::instance()->getDetailPost($id);

        if (!$postInfo) {
            abort(404);
        }

        if ($postInfo->code !== $code) {
            return redirect(route('post.detail', [$postInfo->code, $postInfo->id]), 302);
        }

        Post::instance()->setExclude($postInfo->id);

        //get detail tags
        $listTag = Tag::instance()->getDetailTags($postInfo->tags);

        //get related post
        $listRelatedPost = Post::instance()->getListPostByUser([
            'user_id' => $postInfo->user_id,
            'status' => config('constants.status.active'),
            'item' => config('constants.post.limit.related'),
        ]);

        return view('frontend.post.detail')->with([
            'postInfo' => $postInfo,
            'listTag' => $listTag,
            'listRelatedPost' => $listRelatedPost,
        ]);
    }
}

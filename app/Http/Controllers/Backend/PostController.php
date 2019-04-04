<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\CreatePostRequest;
use App\Http\Requests\Backend\UpdatePostRequest;
use App\Library\Models\Category;
use App\Library\Models\Post;
use App\Library\Models\Tag;
use App\Library\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PostController extends BackendController
{
    public function index(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //search data
        $params = [
            'category_id' => $category_id,
            'status' => $status,
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page,
        ];
        $listPost = Post::instance()->getListPostBE($params);

        if ($listPost->total() > 0) {
            $maxPage = ceil($listPost->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.post.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $listPost->onEachSide(config('site.general.pagination.each_side'))->appends($params)->links();

        $listCategory = Category::instance()->getListParentBE();

        return view('backend.post.index')->with([
            'params' => $params,
            'listPost' => $listPost,
            'pagination' => $pagination,
            'listCategory' => $listCategory,
        ]);
    }

    public function create()
    {
        $listCategory = Category::instance()->getListParentBE();

        return view('backend.post.create')->with(['listCategory' => $listCategory]);
    }

    public function store(CreatePostRequest $request)
    {
        DB::beginTransaction();

        try {
            //process save image from content in other website
            $content = CommonService::processImageContent($request->content);

            $image = $request->thumbnail_url ?? null;
            if ($image) {
                $image = CommonService::saveImageFromTmp($image, 'image');
            } else {
                $image = config('constants.image.default.file');
            }

            $params = [
                'title' => clean($request->title, 'notags'),
                'code' => $request->code,
                'thumbnail_url' => $image,
                'description' => clean($request->description, 'notags'),
                'content' => $content,
                'priority' => $request->priority,
                'tags' => $request->tags,
                'status' => $request->status,
                'show_comment' => config('constants.post.comment.show'),
                'seo_title' => clean($request->seo_title ?? $request->title, 'notags'),
                'seo_keywords' => clean($request->seo_keywords, 'notags'),
                'seo_description' => clean($request->seo_description ?? $request->description, 'notags'),
                'category_id' => $request->category_id,
                'category_liston' => $request->category_liston,
                'user_id' => auth()->user()->getAuthIdentifier(),
            ];

            //make score if status is published
            if ($params['status'] == config('constants.status.active')) {
                $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $params['score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['priority'] . format_date($params['published_at'], 'His');
            }

            Post::instance()->createPost($params);
            Tag::instance()->createTags($request->tags);

            DB::commit();

            return redirect()->route('backend.post.index')->withInput(['message' => ['Add new post successfully!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.post.create')->withInput(['error', ['Add new post fails!']]);
        }
    }

    public function edit($id)
    {
        $postInfo = Post::instance()->getDetailPostBE($id);
        if (!$postInfo) {
            abort(404);
        }

        $listCategory = Category::instance()->getListParentBE();

        return view('backend.post.edit')->with([
            'postInfo' => $postInfo,
            'listCategory' => $listCategory,
        ]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $postInfo = Post::instance()->getDetailPostBE($id);
        if (!$postInfo) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            //process save image from content in other website
            $content = CommonService::processImageContent($request->content);

            $image = $request->thumbnail_url ?? null;
            if ($image) {
                $image = CommonService::saveImageFromTmp($image, 'image');

                if (!$image) {
                    $image = $postInfo->thumbnail_url;
                }
            } else {
                $image = $postInfo->thumbnail_url;
            }

            $params = [
                'title' => clean($request->title, 'notags'),
                'code' => $request->code,
                'thumbnail_url' => $image,
                'description' => clean($request->description, 'notags'),
                'content' => $content,
                'priority' => $request->priority,
                'tags' => $request->tags,
                'status' => $request->status,
                'show_comment' => config('constants.post.comment.show'),
                'seo_title' => clean($request->seo_title ?? $request->title, 'notags'),
                'seo_keywords' => clean($request->seo_keywords, 'notags'),
                'seo_description' => clean($request->seo_description ?? $request->description, 'notags'),
                'category_id' => $request->category_id,
                'category_liston' => $request->category_liston,
            ];

            //make score if status is published
            if ($postInfo->status != config('constants.status.active')) {
                if ($params['status'] == config('constants.status.active')) {
                    $params['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $params['score'] = format_date($params['published_at'], 'Ymd') . '0' . $params['priority'] . format_date($params['published_at'], 'His');
                }
            } else {
                $params['score'] = format_date($postInfo->published_at, 'Ymd') . '0' . $params['priority'] . format_date($postInfo->published_at, 'His');
            }

            Post::instance()->updatePost($params, $id);
            Tag::instance()->createTags($request->tags);

            DB::commit();

            return redirect()->route('backend.post.index')->withInput(['message' => ['Update post successfully!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.post.edit', [$id])->withInput(['error' => ['Update post fails!']]);
        }
    }

    public function changeStatus(Request $request)
    {
        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            foreach ($arrId as $key => $value) {
                Post::instance()->changeStatus($value);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Change Status Successfully !']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            $modelPost = Post::instance();

            foreach ($arrId as $id) {
                $modelPost->deletePost($id);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Done']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }
}

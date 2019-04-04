<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Models\Comment;
use App\Library\Models\Post;
use App\Library\Services\CommonService;
use App\Library\Services\Jobs\UpdateCommentLike;
use App\Library\Services\Jobs\UpdatePostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function getWidget(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        try {
            $cid = $request->cid ?? [];
            $pid = $request->pid ?? [];

            $arDataComment = Comment::instance()->countComment($cid);
            $arDataPost = Post::instance()->countPost($pid);

            return response()->json(['error' => 0, 'data' => ['comment' => $arDataComment, 'post' => $arDataPost]]);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }

    public function getComment(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $post_id = $request->post_id;
        $limit = $request->limit ?? config('constants.post.limit.comment');
        $page = $request->page ?? 1;

        $listComments = Comment::instance()->getListCommentByPost([
            'post_id' => $post_id,
            'item' => $limit,
            'page' => $page,
        ]);
        $pagination = $listComments->withPath(route('interaction.comment', ['post_id' => $post_id, 'limit' => $limit]))->onEachSide(config('site.general.pagination.each_side'))->links();

        //get total comment (include child)
        $total = Comment::instance()->countComment($post_id);

        return view('frontend.layouts.partials.box_comment')->with([
            'total' => $total,
            'listComments' => $listComments,
            'pagination' => $pagination,
            'page' => $page,
        ])->render();
    }

    public function postComment(Request $request)
    {
        if (!auth()->check() || !$request->ajax()) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $params = $request->except('_token');
            $params['user_id'] = auth()->user()->getAuthIdentifier();
            $params['content'] = clean($params['content'], 'notags');
            $params['status'] = config('constants.status.active');

            Comment::instance()->createComment($params);

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Cảm ơn bạn đã gửi bình luận.']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }

    public function updateView(Request $request)
    {
        $postInfo = Post::instance()->getDetailPost($request->id);

        if (!$postInfo) {
            return response()->json(['error' => 1, 'message' => 'Bài viết không tồn tại.']);
        }

        try {
            //update post view
            dispatch(new UpdatePostView([
                'request' => $request->all(),
                'id' => $postInfo->id
            ]));

            return response()->json(['error' => 0, 'message' => 'Done.']);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }

    public function updateLikeComment(Request $request)
    {
        $commentInfo = Comment::instance()->getDetailComment($request->id);

        if (!$commentInfo) {
            return response()->json(['error' => 1, 'message' => 'Bình luận không tồn tại.']);
        }

        try {
            //update post view
            dispatch(new UpdateCommentLike([
                'request' => $request->all(),
                'id' => $commentInfo->id
            ]));

            return response()->json(['error' => 0, 'message' => 'Done.']);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Library\Models\Comment;
use App\Library\Services\CommonService;
use App\Http\Requests\Backend\CommentRequest;

class CommentController extends BackendController
{
    public function index(Request $request)
    {
        $content = $request->content ?? '';
        $item = check_paging($request->item ?? '');
        $page = $request->page ?? 1;

        //search data
        $params = [
            'content' => $content,
            'item' => $item,
            'page' => $page
        ];
        $arrListComment = Comment::instance()->getListCommentBE($params);

        if ($arrListComment->total() > 0) {
            $maxPage = ceil($arrListComment->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.comment.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListComment->onEachSide(config('site.general.pagination.each_side'))->appends($params)->links();

        return view('backend.comments.index')->with([
            'params' => $params,
            'listComment' => $arrListComment,
            'pagination' => $pagination
        ]);
    }

    public function edit($id)
    {
        $data = Comment::instance()->getDetailCommentBE($id);

        if (!$data) {
            abort(404);
        }

        return view('backend.comments.edit')->with(['data' => $data]);
    }

    public function update(CommentRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $modelComment = Comment::instance();

            $modelComment->updateComment($id, [
                'content' => clean($request['content'], 'notags'),
                'status' => $request['status']
            ]);

            DB::commit();

            return redirect(route('backend.comment.index'))->withInput(['message' => ['Update successfully!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect(route('backend.comment.edit', [$id]))->withInput(['error' => ['Update failed!']]);
        }
    }

    public function delete(Request $request)
    {
        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            $modelComment = Comment::instance();

            foreach ($arrId as $id) {
                $modelComment->deleteComment($id);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Done']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }

    public function changeStatus(Request $request)
    {
        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            $modelComment = Comment::instance();
            foreach ($arrId as $key => $value) {
                $modelComment->changeStatus($value);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Change Status Successfully !']);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => $ex->getMessage()]);
        }
    }
}

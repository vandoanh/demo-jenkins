<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use App\Library\Models\Notice;
use Illuminate\Support\Facades\DB;
use App\Library\Services\CommonService;
use App\Http\Requests\Backend\CreateNoticeRequest;
use App\Http\Requests\Backend\UpdateNoticeRequest;
use Carbon\Carbon;
use App\Library\Services\Jobs\PushNotification;
use App\Library\Services\Jobs\PushChatwork;

class NoticeController extends BackendController
{
    public function index(Request $request)
    {
        $status = $request->status ?? null;
        $title = $request->title ?? null;
        $date_from = $request->date_from ?? null;
        $date_to = $request->date_to ?? null;
        $page = $request->page ?? 1;
        $item = check_paging($request->item);

        //search data
        $params = [
            'status' => $status,
            'title' => $title,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'item' => $item,
            'page' => $page
        ];
        $listNotice = Notice::instance()->getListNoticeBE($params);

        if ($listNotice->total() > 0) {
            $maxPage = ceil($listNotice->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.notice.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $listNotice->onEachSide(config('site.general.pagination.each_side'))->appends($params)->links();

        return view('backend.notice.index')->with([
            'params' => $params,
            'listNotice' => $listNotice,
            'pagination' => $pagination,
        ]);
    }

    public function create()
    {
        return view('backend.notice.create');
    }

    public function store(CreateNoticeRequest $request)
    {
        DB::beginTransaction();

        try {
            $params = [
                'title' => clean($request->title, 'notags'),
                'content' => $request->content,
                'content_chatwork' => clean($request->content_chatwork, 'content_chatwork'),
                'push_notification' => $request->push_notification,
                'push_chatwork' => $request->push_chatwork,
                'status' => $request->status,
                'published_at' => $request->published_at ? Carbon::createFromFormat('d/m/Y H:i', $request->published_at, auth()->user()->timezone)->setTimezone('UTC')->format('Y-m-d H:i:0') : Carbon::now()->format('Y-m-d H:i:s'),
                'user_id' => auth()->user()->getAuthIdentifier(),
            ];

            $noticeInfo = Notice::instance()->createNotice($params);

            if ($params['push_notification'] == config('constants.notice.notification.yes')) {
                dispatch(new PushNotification([
                    'user_id' => null,
                    'data' => [
                        'title' => $params['title'],
                        'message' => 'You have received a push message.',
                        'url' => route('notice.detail', [$noticeInfo->id]),
                    ],
                ]));
            }

            if ($params['push_chatwork'] == config('constants.notice.chatwork.yes')) {
                dispatch(new PushChatwork([
                    'title' => $params['title'],
                    'message' => 'You have received a push message.',
                    'url' => route('notice.detail', [$noticeInfo->id]),
                ]));
            }

            DB::commit();

            return redirect()->route('backend.notice.index')->withInput(['message' => ['Add new notice successfully!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.notice.create')->withInput(['error', ['Add new notice failed!']]);
        }
    }

    public function edit($id)
    {
        $noticeInfo = Notice::instance()->getDetailNoticeBE($id);
        if (!$noticeInfo) {
            abort(404);
        }

        return view('backend.notice.edit')->with([
            'noticeInfo' => $noticeInfo
        ]);
    }

    public function update(UpdateNoticeRequest $request, $id)
    {
        $noticeInfo = Notice::instance()->getDetailNoticeBE($id);
        if (!$noticeInfo) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $params = [
                'title' => clean($request->title, 'notags'),
                'content' => $request->content,
                'content_chatwork' => clean($request->content_chatwork, 'content_chatwork'),
                'push_notification' => $request->push_notification,
                'push_chatwork' => $request->push_chatwork,
                'status' => $request->status,
                'published_at' => $request->published_at ? Carbon::createFromFormat('d/m/Y H:i', $request->published_at, auth()->user()->timezone)->setTimezone('UTC')->format('Y-m-d  H:i:0') : null,
            ];

            Notice::instance()->updateNotice($params, $id);

            if ($params['push_notification'] == config('constants.notice.notification.yes')) {
                dispatch(new PushNotification([
                    'user_id' => null,
                    'data' => [
                        'title' => $params['title'],
                        'message' => 'You have received a push message.',
                        'url' => route('notice.detail', [$noticeInfo->id]),
                    ],
                ]));
            }

            if ($params['push_chatwork'] == config('constants.notice.chatwork.yes')) {
                dispatch(new PushChatwork([
                    'title' => $params['title'],
                    'message' => 'You have received a push message.',
                    'url' => route('notice.detail', [$noticeInfo->id]),
                ]));
            }

            DB::commit();

            return redirect()->route('backend.notice.index')->withInput(['message' => ['Update notice successfully!']]);
        } catch (\Exception $ex) {
            DB::rollBack();
            CommonService::logError($request, $ex);

            return redirect()->route('backend.notice.edit', [$id])->withInput(['error' => ['Update notice failed!']]);
        }
    }

    public function changeStatus(Request $request)
    {
        $arrId = $request->id ?? [];

        DB::beginTransaction();

        try {
            foreach ($arrId as $value) {
                Notice::instance()->changeStatus($value);
            }

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Change status successfully !']);
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
            foreach ($arrId as $id) {
                Notice::instance()->deleteNotice($id);
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

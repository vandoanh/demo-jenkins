<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Models\Chat;
use App\Library\Services\Events\PublicMessage;
use Illuminate\Http\Request;
use App\Library\Services\Jobs\Chat as JobChat;
use App\Library\Services\CommonService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $listMessage = Chat::instance()->getListMessage();
        $user_id = Chat::instance()->makeUserId(csrf_token());

        return view('frontend.chat.index')->with([
            'listMessage' => $listMessage,
            'user_id' => $user_id
        ]);
    }

    public function send(Request $request)
    {
        try {
            dispatch(new JobChat([
                'user_id' => auth()->check() ? auth()->user()->getAuthIdentifier() : 'User ' . $request->user_id,
                'message' => clean($request->message, 'notags'),
                'ip_address' => $request->getClientIp(),
                'user_agent' => $request->header('User-Agent'),
                'cookie_val' => auth()->check() ? auth()->getSession()->getId() : '',
            ]));

            $messageInfo = [
                'id' => Str::random(10),
                'user' => auth()->check() ? auth()->user()->getAuthIdentifier() : null,
                'user_id' => 'User ' . $request->user_id,
                'message' => clean($request->message, 'notags'),
                'created_at' => Carbon::now(),
            ];
            $message = view('frontend.chat.message')->with(['messageInfo' => $messageInfo])->render();

            broadcast(new PublicMessage($message))->toOthers();

            return response()->json(['error' => 0, 'message' => 'Done!', 'data' => $message]);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);

            return response()->json(['error' => 1, 'message' => 'Error!']);
        }
    }
}

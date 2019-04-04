<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Models\NotificationSubscription;
use App\Library\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, [
                'endpoint' => 'required'
            ]);

            NotificationSubscription::instance()->insertSubscription([
                'endpoint' => $request->endpoint,
                'public_key' => $request->public_key ?? null,
                'auth_token' => $request->auth_token ?? null,
                'content_encoding' => $request->content_encoding ?? null,
                'user_id' => auth()->check() ? auth()->user()->getAuthIdentifier() : 0,
            ]);

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Subscribe succeed!']);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);
            DB::rollBack();

            return response()->json(['error' => 1, 'message' => 'Error!']);
        }
    }

    public function unsubscribe(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, [
                'endpoint' => 'required'
            ]);

            NotificationSubscription::instance()->deleteByEndpoint($request->endpoint);

            DB::commit();

            return response()->json(['error' => 0, 'message' => 'Unsubscribe succeed!']);
        } catch (\Exception $ex) {
            CommonService::logError($request, $ex);
            DB::rollBack();

            return response()->json(['error' => 1, 'message' => 'Error!']);
        }
    }
}

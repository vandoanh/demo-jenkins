<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Library\Models\User;
use App\Library\Models\UserSocial;
use App\Library\Services\CommonService;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Library\Services\Jobs\WriteActiveLog;

class SocialController extends Controller
{
    public function __construct()
    {
        if (!config('site.general.social_login.enable')) {
            abort(404);
        }
    }

    public function login($provider)
    {
        if (empty(config('services.' . $provider))) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handle(Request $request, $provider)
    {
        if (!config('site.general.social_login.' . $provider . '.enable') || empty(config('services.' . $provider))) {
            abort(404);
        }

        if ($request->denied != '') {
            return redirect(route('home'));
        }

        $user = Socialite::driver($provider)->user();
        $socialUser = null;

        //get model
        $modelUser = User::instance();
        $modelUserSocial = UserSocial::instance();

        //check user exists
        $userCheck = $modelUser->getDetailUserByEmail($user->email);

        if ($userCheck) {
            $socialUser = $userCheck;
        } else {
            //check if has avatar
            $avatar = config('constants.image.avatar.name');
            if (isset($user->avatar) && $provider === 'google') {
                $avatar = str_replace('?type=normal', '?type=large', $user->avatar);
                $avatar = str_replace('?sz=50', '', $avatar);

                $avatar = CommonService::saveImageFromUrl($avatar, config('constants.image.avatar.folder'), true);
                if (!$avatar) {
                    $avatar = config('constants.image.avatar.name');
                }
            }

            //There is no combination of this social id and provider, so create new one
            $socialUser = $modelUser->createUser([
                'fullname' => $user->name,
                'email' => $user->email,
                'avatar' => $avatar,
                'status' => config('constants.user.status.active'),
            ]);
        }

        $modelUserSocial->createUser([
            'user_id' => $socialUser->id,
            'social_id' => $user->id,
            'provider' => $provider
        ]);

        auth()->login($socialUser, true);

        // write log
        dispatch(new WriteActiveLog([
            'user_id' => auth()->user()->getAuthIdentifier(),
            'module' => config('constants.log.module.frontend'),
            'type' => config('constants.log.type.login'),
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->header('User-Agent'),
            'cookie_val' => auth()->getSession()->getId(),
        ]))->onQueue('log');

        return redirect(route('home'));
    }
}

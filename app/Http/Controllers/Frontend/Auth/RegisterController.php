<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RegisterRequest;
use App\Library\Models\Token;
use App\Library\Models\User;
use App\Library\Services\Notifications\MailActiveAccount;
use Carbon\Carbon;

class RegisterController extends Controller
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        $this->redirectTo = route('auth.message');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function processRegister(RegisterRequest $request)
    {
        $userInfo = User::instance()->createUser([
            'email' => $request->email,
            'fullname' => $request->fullname,
            'birthday' => $request->birthday ? Carbon::createFromFormat('d/m/Y', $request->birthday) : null,
            'gender' => $request->gender,
            'avatar' => config('constants.image.avatar.name'),
            'password' => bcrypt($request->password),
            'user_type' => config('constants.user.type.member'),
            'status' => config('constants.status.inactive')
        ]);

        //create token link to active account
        $key = Token::instance()->insertTokenKey([
            'type' => config('constants.token.type.active_account'),
            'user_id' => $userInfo->id,
        ]);

        //send mail verify account
        $userInfo->notify(new MailActiveAccount([
            'url' => route('auth.verify', [$key])
        ]));

        return redirect($this->redirectTo)->withInput(['message' => ['Please check mail to active your account.']]);
    }
}

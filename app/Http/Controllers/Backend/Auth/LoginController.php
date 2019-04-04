<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\BackendController;
use App\Http\Requests\Backend\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Library\Services\Jobs\WriteActiveLog;

class LoginController extends BackendController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected $redirectAfterLogout;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:' . $this->guard)->except('logout');

        $this->redirectTo = route('backend.dashboard');
        $this->redirectAfterLogout = route('backend.auth.login');
    }

    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    public function processLogin(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (auth($this->guard)->attempt(['email' => $request->email, 'password' => $request->password, 'status' => config('constants.user.status.active'), 'user_type' => config('constants.user.type.admin')], $request->has('remember'))) {
            // Authentication passed...
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        dispatch(new WriteActiveLog([
            'user_id' => auth($this->guard)->user()->getAuthIdentifier(),
            'module' => config('constants.log.module.backend'),
            'type' => config('constants.log.type.logout'),
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->header('User-Agent'),
            'cookie_val' => auth()->getSession()->getId(),
        ]))->onQueue('log');

        auth($this->guard)->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : route('backend.auth.login'));
    }

    public function authenticated(Request $request)
    {
        // write log
        dispatch(new WriteActiveLog([
            'user_id' => auth($this->guard)->user()->getAuthIdentifier(),
            'module' => config('constants.log.module.backend'),
            'type' => config('constants.log.type.login'),
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->header('User-Agent'),
            'cookie_val' => auth()->getSession()->getId(),
        ]))->onQueue('log');

        //redirect to landing page
        return redirect()->intended($this->redirectTo);
    }
}

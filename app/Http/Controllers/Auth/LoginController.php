<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Base\Usuario;
use Validator, Auth;

class LoginController extends Controller
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
     protected $redirectTo = '/';

     protected $loginPath = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'integrate'] ]);
    }

    /**
    * Override the username method used to validate login
    *
    * @return string
    */
    public function username()
    {
        return 'usuario_id';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if( $this->attemptLogin($request) ) {
            return $this->sendLoginResponse($request);
        }else{
            // Support md5 passwords
            $user = Usuario::where('usuario_id', $request->usuario_id)->where('usuario_activo', true)->first();
            if( $user instanceof Usuario && $user->usuario_password == md5($request->password) ) {
                Auth::login($user);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle a integrate login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function integrate(Request $request)
    {
        if($request->has('usuario_id')) {
            $user = Usuario::where('usuario_id', $request->usuario_id)->where('usuario_activo', true)->firstOrFail();
            Auth::login($user);

            return redirect()->route($request->has('redirect') ? $request->redirect : 'dashboard');
        }
        abort(404);
    }
}

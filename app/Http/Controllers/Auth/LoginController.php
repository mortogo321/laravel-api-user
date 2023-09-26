<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->getUsername();
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username => 'required|string',
            'password' => 'required|string',
            'orgUnitCode' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username, 'password', 'orgUnitCode');
    }

    protected function getUserName()
    {
        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'loginId';
        request()->merge([$fieldType => $login]);

        return $fieldType ?? 'loginId';
    }

    protected function username()
    {
        return $this->username;
    }
}

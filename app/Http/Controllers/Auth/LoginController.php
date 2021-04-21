<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
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

    protected function authenticated(Request $request, $user)
    {
        return $user;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        $request->session()->regenerate();

        return response()->json();
    }

    // ゲストログイン処理
    public function guestLogin()
    {
        $email = 'guest@example.com';
        $password = 'guest123';

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json();
        }

        return response()->json();
    }
}

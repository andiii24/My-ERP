<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->isAccessAllowed()) {
            $user->last_online_at = now();
            $user->save();
        }
    }

    public function loggedOut()
    {
        return redirect('/login');
    }
}

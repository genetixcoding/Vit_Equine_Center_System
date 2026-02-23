<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
    // protected $redirectTo = '/home';
      public function authenticated() {
        if (Auth::user()->role_as == '1') {
            return redirect('Dashboard')->with('status', 'Welcome to Admin Dashboard !!');
        }
        else if (Auth::user()->role_as == '2') {

            return redirect('Supervisor')->with('status', 'Welcome to Supervisor Dashboard !!');
            }
        // role_as = 0 and major != 0 (major is 1,2,3,4)
        else if (Auth::user()->role_as == '0' && Auth::user()->major != '0') {

            return redirect('Staff')->with('status', 'Logged In Successfully. Welcome !!');
        }
        // role_as = 0 and major = 0
        else if (Auth::user()->role_as == '0' && Auth::user()->major == '0') {

            return redirect('Home')->with('status', 'Logged In Successfully. Welcome !!');
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}

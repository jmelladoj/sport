<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        if(!Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password])){
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
        
        $usuario = Auth::user();
        $token = Auth::user()->createToken('auth_token')->accessToken;

        return response()->json(
            [
                'usuario' => $usuario,
                'access_token' => $token
            ]
        );
    }

    public function logout(Request $request){
        Auth::user()->token()->revoke();    
    }
}

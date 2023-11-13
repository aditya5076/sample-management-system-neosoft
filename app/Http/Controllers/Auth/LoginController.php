<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Helpers\Helper;

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
    private $code = 'access_prohibited';
    private $status = 'You are not authorised to perform the following operation. Kindly contact Admin!';
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    // protected $redirectTo = '/request-listing';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
        * This function is customised login function which overrides the default login procedures & redirects intended login as per situations.
        * @param Request : $request ( Email / Password )
        * @return View : Intended route
        * @return Validation
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
        {
            if((Auth::User()->is_active) == 1)
            {
                if((Helper::returnAuthRole() == "Image Uploader"))
                {
                    return redirect()->intended(route('uploadForm'));
                }
                else
                {
                    return redirect()->intended(route('showScreen'));
                }
            }
            else
            {
                Auth::logout();
                return redirect('/login')->with($this->code,$this->status);
            }
        }
        else 
        {
            return $this->sendFailedLoginResponse($request);
        }
    }

    /**
        * This function is customised logout function which overrides the default logout procedures & redirects intended login as per situations.
        * @return View : Intended route
        * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */
    public function logout(Request $request) 
    {
        Auth::logout();
        return redirect('/login');
    }
}

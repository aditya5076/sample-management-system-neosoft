<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class AdminACL
{
    /**
     * Handle an incoming request. This middleware grants access only to Admin role.
     * @access Rights : Admin
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    private $code = 'access_prohibited';
    private $status = 'Kindly login as Admin to perform following operation!';
    public function handle($request, Closure $next)
    {
        if((Helper::returnAuthRole() == "Admin") || (Helper::returnAuthRole() == 'Super Admin')){
            return $next($request);
        }else{
            Auth::logout();
            return redirect('/login')->with($this->code,$this->status);
        }
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class iuACL
{
    /**
     * Handle an incoming request. This middleware grants access only to Image Uploader role.
     * @access Rights : Image Uploader
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    private $code = 'access_prohibited';
    private $status = 'Kindly login as Image-Uploader to perform following operation!';
    public function handle($request, Closure $next)
    {
        if((Helper::returnAuthRole() == "Image Uploader") || (Helper::returnAuthRole() == 'Super Admin')){
            return $next($request);
        }else{
            Auth::logout();
            return redirect('/login')->with($this->code,$this->status);
        }
    }
}

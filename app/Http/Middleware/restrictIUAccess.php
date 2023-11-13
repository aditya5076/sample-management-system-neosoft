<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class restrictIUAccess
{
    /**
     * Handle an incoming request. This middleware restricts Image Uploader access on route groups attached.
     * @access Rights : Image Uploader
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    private $code = 'access_prohibited';
    private $status = 'You are not authorised to perform the following operation!';
    public function handle($request, Closure $next)
    {
        if((Helper::returnAuthRole() == "Image Uploader")){
            Auth::logout();
            return redirect('/login')->with($this->code,$this->status);
        }
        return $next($request);
    }
}

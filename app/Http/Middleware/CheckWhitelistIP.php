<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class CheckWhitelistIP
{
    /**
     * Handle an incoming request. This middleware checks whether client accessing IP is whitelist IP of Sutlej connections or not.
     * @access Rights : Sutlej Connections
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    private $whitelisted_sutlej_ips;
    const run_cron = 1;
    /**
        * @Initialization constructor
    */
    public function __construct()
    {
        $this->whitelisted_sutlej_ips = Helper::whitelisted_sutlej_ip_addresses('Sutlej Connections IP Whitelisting');
    } // end : construct

    public function handle($request, Closure $next)
    {
        if(in_array(self::run_cron,Helper::custom_config_procedures('Sutlej Connections IP Whitelisting')))
        {
            if (!in_array($request->ip(), $this->whitelisted_sutlej_ips)) 
            {
                return abort(403, 'Forbidden, Access prohibited!');
            }
            return $next($request);
        }else
        {
            return $next($request);
        }
    }
}

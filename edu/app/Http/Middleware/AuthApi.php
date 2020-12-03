<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Config;
use App\Providers\Requestapi;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if($request->session()->get('username') == 'admin' && $request->session()->get('password') == 'admin'){
        //     return $next($request);
        // }else{
        //     return \redirect()->route('login');
        // }
        $token = $request->session()->get('ACCESS_TOKEN');
        if($token == null){
            return \redirect()->route('login');
        }else{
            $response = new Requestapi('/api/v1/student-list?type=absent',['Authorization' => $token]);
            for($i=0; $i <= 3; $i++){
                set_time_limit(20);
                $data = json_decode($response->methodGet());
                if(!isset($data->error_code)){
                    break;
                }
            }
            if(isset($data->error_code)){
                return \redirect()->route('login');
            }else{
                return $next($request);
            }
        }
        
    }
}

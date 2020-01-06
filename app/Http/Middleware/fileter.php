<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class fileter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($_SERVER['HTTP_TOKEN']))
        {
            $redis_key = 'str:count:u:'.$_SERVER['HTTP_TOKEN'] . ':url:' . $_SERVER['REQUEST_URI'];
            $count = Redis::get($redis_key);
            if ($count >=5){
                Redis::expire($redis_key,5);
                $response = [
                    'erron' => 400004,
                    'msg'   => '接口请求已达上限，请稍后再试'
                ];
            }else{
                $response = [
                    'erron' => 400003,
                    'msg'   => "未授权"
                ];
            }
        }
        return $next($request);
    }
}

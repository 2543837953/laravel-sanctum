<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    //服务器端自动加载 Authorization 信息
    public function handle($request, Closure $next, ...$guards)
    {
        if ($token=$request->cookie('token')){
            $request->headers->set("Authorization",'Bearer '.$token);
        }
        $this->authenticate($request,$guards);
        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}

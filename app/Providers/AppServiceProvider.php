<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->addAcceptableJsonType();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    //服务器端设定 Accept
    public function addAcceptableJsonType(){
        $this->app->rebinding('request',function ($app,$request){
            $accept=$request->header('Accept');
            if (!str_contains($accept,'/json')||!str_contains($accept,'+json')){
                $accept=rtrim('application/json'.$accept,',');
                $request->headers->set('Accept',$accept);
                // 顺手设定一下服务器的 HTTP_ACCEPT，可以不要，只是以防用到此信息
                $request->server->set('HTTP_ACCEPT',$accept);
                $_SERVER['HTTP_ACCEPT']=$accept;
            }
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });

        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(100);
        });

        /*RateLimiter::for('vistas', function (Request $request) {
            return Limit::perMinute(2)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('contenido', function (Request $request){
            return Limit::perMinute(2)->response(function (){
                return response('Ha excedido el número de intentos', 429);
            });
        });*/

        RateLimiter::for('cont_admin_vistas', function (Request $request){
            return Limit::perMinute(10)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('cont_admin_query_login', function (Request $request) {
            return Limit::perMinute(8)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('cont_admin_query', function (Request $request) {
            return Limit::perMinute(10)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });


        RateLimiter::for('cont_user_vistas', function (Request $request){
            return Limit::perMinute(15)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429_user', [], 429);
            });
        });

        RateLimiter::for('vista_personalizada', function (Request $request) {
            return Limit::perMinute(2)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429_user', [], 429);
            });
        });

        RateLimiter::for('cont_user_query', function (Request $request) {
            return Limit::perMinute(5)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429_user', [], 429);
            });
        });

        RateLimiter::for('chat_users', function (Request $request) {
            return Limit::perMinute(1)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429_user', [], 429);
            });
        });
/******************************************************************************************************************************/
// RUTAS DE ADMINISTRACIÓN
/******************************************************************************************************************************/
        RateLimiter::for('limit_admin_view', function (Request $request) {
            return Limit::perMinute(100)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('limit_admin_select', function (Request $request) {
            return Limit::perMinute(150)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('limit_admin_insert', function (Request $request) {
            return Limit::perMinute(150)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('limit_admin_update', function (Request $request) {
            return Limit::perMinute(150)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });

        RateLimiter::for('limit_admin_delete', function (Request $request) {
            return Limit::perMinute(100)->by(optional($request->user())->id ?: $request->ip())
            ->response(function (){
                //return response('Ha excedido el número de intentos', 429);
                return response()->view('Errores.429', [], 429);
            });
        });
    }
}

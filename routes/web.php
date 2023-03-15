<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Router;


$router->get('/status', function (Request $request) use ($router) {
    phpinfo();

    try {
        DB::connection()->getPdo();

        return 'ok';
    } catch (\Exception $e) {
        return 'Errore di connessione al database: ' . $e->getMessage();
    }
});

$router->get('/test',function (Request $request) use ($router)
{
    try{

        $user = DB::table('user')->get();
        return 'ok';
    }catch (\Exception $e){
        return $e->getMessage();
    }
});

/*Route dei post*/

$router->group(['prefix' => 'posts'], function () use ($router) {
    $router->get('', 'PostController@showAll'); //ok
    $router->get('{id}', 'PostController@show'); //ok


});

/*Route dei commenti*/

$router->group(['prefix' => 'comments'], function () use ($router) {

    $router->get('', 'CommentController@showComment');//ok
    $router->get('{commentId}', 'CommentController@show');//ok

});


$router->group(['middleware' => ['auth']], function () use ($router) {

    $router->group(['prefix' => 'comments'], function () use ($router) {
        $router->delete('{commentId}', 'CommentController@delete'); //ok
        $router->post('', 'CommentController@create'); //ok
        $router->put('{commentId}', 'CommentController@update'); //ok
    });

    $router->group(['prefix' => 'posts'], function () use ($router) {
        $router->post('', 'PostController@create'); //ok
        $router->put('{id}', 'PostController@update'); //ok
        $router->delete('{id}', 'PostController@deletePost'); //ok

    });
});



/*Route degli utenti*/

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register'); //ok
    $router->post('/login', 'AuthController@login'); //ok
    $router->post('/check', 'AuthController@check'); //ok

});

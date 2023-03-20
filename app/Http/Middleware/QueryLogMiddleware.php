<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class QueryLogMiddleware
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

        DB::enableQueryLog();
        $response = $next($request);

        $queries = DB::getQueryLog();

        $data = json_decode($response->getContent(), true);


        $response->setContent(json_encode([
            'data' => $data,
            'queries' => $queries
        ]));

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleMultipartPut
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('put') && $request->header('Content-Type') === 'multipart/form-data') {
            $request->merge($request->all());
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->header('X-Lang') ?? 'ru';
        $allowedLocales = ['en', 'ru'];
        if(!\in_array($lang, $allowedLocales)){
            return response()->json(['error_lang' => 'Only "ru" or "en"']);
        }
        app()->setLocale($lang);
        return $next($request);
    }
}

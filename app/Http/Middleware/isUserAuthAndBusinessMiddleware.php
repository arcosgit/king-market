<?php

namespace App\Http\Middleware;

use App\Models\BusinessModel;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserAuthAndBusinessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return redirect()->route('user.profile');
        }
        $bussines = BusinessModel::where('user_id', auth()->id())->first();
        if($bussines == null){
            return redirect()->route('user.profile');
        }
        $request->attributes->set('user_bussines', $bussines);
        return $next($request);
    }
}

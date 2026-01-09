<?php

namespace App\Http\Middleware;

use App\Models\ProductModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserOwnerProductMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_bussines = $request->attributes->get('user_bussines');
        $product_id = $request->route('id');
        $product = ProductModel::where('business_id', $user_bussines->id)->where('id', $product_id)->with('images', 'characteristics', 'categories')->first();
        if($product == null){
            return redirect()->route('index');
        }
        $request->attributes->set('product', $product);
        return $next($request);
    }
}

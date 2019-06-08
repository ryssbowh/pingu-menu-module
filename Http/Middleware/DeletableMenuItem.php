<?php

namespace Pingu\Menu\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pingu\Core\Exceptions\ProtectedModel;
use Pingu\Menu\Entities\MenuItem;

class DeletableMenuItem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $item = $request->route()->parameters[MenuItem::routeSlug()];
        if($item and !$item->deletable){
            throw ProtectedModel::forDeletion($item);
        }
        return $next($request);
    }
}

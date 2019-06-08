<?php

namespace Pingu\Menu\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pingu\Core\Exceptions\ProtectedModel;
use Pingu\Menu\Entities\Menu;

class DeletableMenu
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
        $menu = $request->route()->parameters[Menu::routeSlug()];
        if($menu and !$menu->deletable){
            throw ProtectedModel::forDeletion($menu);
        }
        return $next($request);
    }
}

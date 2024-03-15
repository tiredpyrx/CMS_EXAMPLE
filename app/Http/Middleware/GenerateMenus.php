<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lavary\Menu\Menu;
use Symfony\Component\HttpFoundation\Response;

class GenerateMenus
{
    public function handle($request, Closure $next)
    {
        (new Menu())->make('MyNavBar', function ($menu) {
            $menu->add('Home');
            $menu->add('About', 'about');
            $menu->add('Services', 'services');
            $menu->add('Contact', 'contact');
        });

        return $next($request);
    }
}

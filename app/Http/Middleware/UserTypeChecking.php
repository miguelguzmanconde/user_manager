<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserTypeChecking {

    public function handle(Request $request, Closure $next): Response {

        $check = Auth::check();
        $user = Auth::user();
        $isAUserLogged = $check && isset($user) && (stripos($user->getTable(), 'users') !== false);
        $isAuthRoute = $request->routeIs('user.*');
        $isGuest = ($isAUserLogged && (stripos($user->type, 'guest') !== false));
        $authRoute = $isGuest ? 'profile.edit' : 'user.list';

        if (($isGuest && !$isAuthRoute) || !$isGuest && $isAuthRoute)
            return $next($request);
        else
            return redirect(route($authRoute));

    }

}
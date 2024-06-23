<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthChecking {

    public function handle(Request $request, Closure $next): Response {

        $check = Auth::check();
        $user = Auth::user();
        $isAUserLogged = $check && isset($user) && (stripos($user->getTable(), 'users') !== false);
        $isGuestRoute = in_array($request->url(), [
                route('register'),
                route('login'),
                route('index')
            ]);

        $isGuest = ($isAUserLogged && (stripos($user->type, 'guest') !== false)) || !$isAUserLogged;
        $authRoute = $isGuest ? 'profile.edit' : 'user.list';

        if (($isAUserLogged && !$isGuestRoute) || (!$isAUserLogged && $isGuestRoute))
            return $next($request);
        elseif ($isAUserLogged && $isGuestRoute)
            return redirect(route($authRoute));
        else
            return redirect(route('login'));

    }

}
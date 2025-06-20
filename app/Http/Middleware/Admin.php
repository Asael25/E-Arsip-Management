<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !in_array($user->name, ['super admin', 'admin'])) {
            return redirect('/')->with('error', 'Tidak memiliki akses.');
        }

        return $next($request);
    }
}

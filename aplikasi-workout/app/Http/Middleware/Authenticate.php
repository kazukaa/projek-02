<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request bukan mengharapkan JSON (bukan API)
        if (! $request->expectsJson()) {
            
            // Cek jika request ditujukan untuk panel admin
            if ($request->routeIs('filament.admin.*')) {
                return route('filament.admin.auth.login');
            }
            
            // Cek jika request ditujukan untuk panel user (app)
            if ($request->routeIs('filament.app.*')) {
                return route('filament.app.auth.login');
            }
        }

        return null; // Untuk request API, jangan redirect, biarkan ia melempar exception
    }
}
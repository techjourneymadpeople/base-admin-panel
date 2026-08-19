<?php

namespace App\Http\Middleware;

use App\Models\WebConfiguration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationIsEnabled
{
    /**
     * Handle an incoming request and redirect to login if registration is disabled.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('register*') || $request->routeIs('register*')) {
            $config = WebConfiguration::current();
            if (!$config->registration_enabled) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Pendaftaran akun baru saat ini sedang ditutup oleh Administrator.',
                    ], 403);
                }

                return redirect()->route('login')
                    ->with('status', 'Pendaftaran akun baru saat ini sedang ditutup oleh Administrator.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\WebConfiguration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePartnerModuleEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $config = WebConfiguration::current();

        if (!$config->partner_module_enabled) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Modul Mitra / Brand Partner saat ini sedang ditutup oleh administrator.',
                ], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Modul Mitra / Brand Partner saat ini sedang ditutup melalui Web Konfigurasi.');
        }

        return $next($request);
    }
}

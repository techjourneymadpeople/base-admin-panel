<?php

namespace App\Http\Middleware;

use App\Models\WebConfiguration;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureArticleModuleEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $config = WebConfiguration::current();

        if (!$config->article_module_enabled) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Modul Artikel (termasuk Kategori dan Tag) saat ini sedang ditutup oleh administrator.',
                ], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Modul Artikel saat ini sedang ditutup melalui Web Konfigurasi.');
        }

        return $next($request);
    }
}

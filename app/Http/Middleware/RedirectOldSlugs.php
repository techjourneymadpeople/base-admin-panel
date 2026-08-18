<?php

namespace App\Http\Middleware;

use App\Models\SlugRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectOldSlugs
{
    /**
     * Handle an incoming request and perform 301 redirect if an old slug is requested.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only inspect safe read requests (GET/HEAD)
        if (!$request->isMethodSafe()) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');

        // Check if there is a registered redirect for this path
        $redirect = SlugRedirect::where('source_path', $path)->first();

        if ($redirect) {
            // Increment hit count asynchronously or silently without updating updated_at
            $redirect->timestamps = false;
            $redirect->increment('hits');

            $targetUrl = url($redirect->target_path);

            // Preserve any query strings attached to request
            if ($queryString = $request->getQueryString()) {
                $targetUrl .= '?' . $queryString;
            }

            return redirect($targetUrl, $redirect->status_code ?: 301);
        }

        return $next($request);
    }
}

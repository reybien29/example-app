<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureExternalIntegrationKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $providedKey = (string) $request->header('X-Integration-Key', '');
        $configuredKeys = config('api_integrations.external.keys', []);

        if (
            $providedKey === ''
            || ! is_array($configuredKeys)
            || ! in_array($providedKey, $configuredKeys, true)
        ) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}

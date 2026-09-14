<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TmdbProxyController extends Controller
{
    /**
     * Whitelist des endpoints TMDB autorisés (prévention SSRF).
     */
    private const ALLOWED_PREFIXES = [
        'trending/',
        'movie/',
        'tv/',
        'search/',
        'discover/',
        'genre/',
        'configuration',
        'find/',
    ];

    public function proxy(Request $request, string $path = '')
    {
        $fullPath = $path;
        if ($request->query()) {
            // On garde les query params validés côté appelant, mais on filtre
            $fullPath .= '?' . http_build_query($request->query());
        }

        // Validation du path — doit commencer par un préfixe autorisé
        $isAllowed = false;
        foreach (self::ALLOWED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint TMDB non autorisé.',
            ], 403);
        }

        // Protection path traversal
        if (str_contains($path, '..') || str_contains($path, '//')) {
            return response()->json([
                'success' => false,
                'message' => 'Chemin invalide.',
            ], 400);
        }

        $bearer = config('services.tmdb.bearer');
        $apiKey = config('services.tmdb.key');
        $baseUrl = rtrim(config('services.tmdb.base_url'), '/');

        if (empty($bearer) && empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Service TMDB non configuré côté serveur.',
            ], 503);
        }

        $cacheKey = 'tmdb_proxy:' . md5($path . '?' . http_build_query($request->query()));

        try {
            $data = Cache::remember($cacheKey, 600, function () use ($baseUrl, $path, $request, $bearer, $apiKey) {
                $url = $baseUrl . '/' . ltrim($path, '/');

                $query = $request->query();
                // Forcer langue FR si non spécifiée
                if (!isset($query['language'])) {
                    $query['language'] = 'fr-FR';
                }

                $http = Http::timeout(8)->retry(1, 200);

                if (!empty($bearer)) {
                    $http = $http->withToken($bearer);
                } elseif (!empty($apiKey)) {
                    $query['api_key'] = $apiKey;
                }

                $response = $http->get($url, $query);

                return [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ];
            });

            return response()->json($data['body'], $data['status']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur proxy TMDB.',
                'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Service indisponible',
            ], 502);
        }
    }

    /**
     * Helper pour générer l'URL d'image (pas de proxy, juste config).
     */
    public function imageConfig()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'image_base_url' => config('services.tmdb.image_base_url'),
                'poster_size' => 'w500',
                'backdrop_size' => 'w1280',
                'profile_size' => 'w185',
            ],
        ]);
    }
}

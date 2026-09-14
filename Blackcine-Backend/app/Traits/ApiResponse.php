<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Réponse de succès
     */
    protected function success($data = null, string $message = 'Succès', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Réponse de création
     */
    protected function created($data = null, string $message = 'Créé avec succès'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Réponse sans contenu
     */
    protected function noContent(string $message = 'Supprimé avec succès'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * Réponse d'erreur
     */
    protected function error(string $message = 'Erreur', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Réponse 404
     */
    protected function notFound(string $message = 'Non trouvé'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Réponse 401
     */
    protected function unauthorized(string $message = 'Non authentifié'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Réponse 403
     */
    protected function forbidden(string $message = 'Accès interdit'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Réponse 422
     */
    protected function unprocessable(string $message = 'Données invalides', $errors = null): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Réponse 429
     */
    protected function tooManyRequests(string $message = 'Trop de requêtes'): JsonResponse
    {
        return $this->error($message, 429);
    }

    /**
     * Réponse paginée
     */
    protected function paginated($paginator, string $message = 'Succès'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ]);
    }
}

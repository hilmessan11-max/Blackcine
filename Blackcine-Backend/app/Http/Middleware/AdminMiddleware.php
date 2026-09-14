<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Vérifier que l'utilisateur est un administrateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié.'
                ], 401);
            }
            return redirect('/login');
        }

        // Vérifier si l'utilisateur a un rôle d'admin
        if (!$user->hasAnyRole(['SuperAdmin', 'Admin'])) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès réservé aux administrateurs.'
                ], 403);
            }
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}

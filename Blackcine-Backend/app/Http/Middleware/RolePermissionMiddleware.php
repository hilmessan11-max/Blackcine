<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolePermissionMiddleware
{
    /**
     * Gérer une requête entrante.
     *
     * Usage dans les routes :
     *  -> middleware('role_permission:Admin')               // rôle seul
     *  -> middleware('role_permission:Admin|SuperAdmin')   // plusieurs rôles
     *  -> middleware('role_permission::edit articles')    // permission seule
     *  -> middleware('role_permission:Admin|SuperAdmin,edit articles') // rôle + permission
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null $roles
     * @param  string|null $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles = null, $permissions = null)
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

        // SuperAdmin a accès à tout
        if ($user->hasRole('SuperAdmin')) {
            return $next($request);
        }

        // Vérifier les rôles
        if ($roles) {
            $roleList = explode('|', $roles);
            if (!$user->hasAnyRole($roleList)) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Accès interdit : rôle non autorisé.'
                    ], 403);
                }
                abort(403, 'Accès interdit : rôle non autorisé.');
            }
        }

        // Vérifier les permissions
        if ($permissions) {
            $permList = explode('|', $permissions);
            $hasPermission = false;
            foreach ($permList as $perm) {
                if ($user->can($perm)) {
                    $hasPermission = true;
                    break;
                }
            }
            if (!$hasPermission) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Accès interdit : permission requise.'
                    ], 403);
                }
                abort(403, 'Accès interdit : permission requise.');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Le paramètre ...$roles permet d'accepter une liste de rôles autorisés
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. L'utilisateur est-il connecté ?
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 2. L'utilisateur a-il un des rôles exigés ?
        $userRole = auth()->user()->role->nom; // Assure-toi que la relation 'role' est bien définie dans User.php
        
        if (!in_array($userRole, $roles)) {
            // SÉCURITÉ : On bloque l'accès net (403 Forbidden)
            abort(403, 'Action bloquée : Vous n\'avez pas les privilèges nécessaires.');
        }

        // Tout est bon, on laisse passer la requête
        return $next($request);
    }
}
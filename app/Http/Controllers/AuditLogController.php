<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        // Sécurité stricte : Admin uniquement
        if (auth()->user()->role->nom !== 'admin') {
            abort(403, 'Accès strictement réservé aux administrateurs.');
        }

        // On récupère les logs du plus récent au plus ancien, avec pagination
        $logs = AuditLog::with('user')->latest()->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}
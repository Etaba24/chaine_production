<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MatierePremiereController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\OrdreFabricationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Routes Publiques (Non-authentifiées)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// Traçabilité par scan de QR Code (Accessible au public ou clients)
Route::get('/trace/{tracking_code}', [OrdreFabricationController::class, 'trace'])->name('trace.production');


/*
|--------------------------------------------------------------------------
| Niveau 1 : Authentifié de base (Ouvriers, Chefs, Admins)
|--------------------------------------------------------------------------
| Tout utilisateur connecté atterrit ici. L'ouvrier a un accès limité 
| à ses propres tâches.
*/
/*
|--------------------------------------------------------------------------
| Routes du Profil Utilisateur (Requises par Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->role->nom === 'ouvrier') {
            return redirect()->route('ordres.mes_ordres');
        }
        return app(DashboardController::class)->index();
    })->name('dashboard');

    // L'ouvrier consulte uniquement les ordres qui lui sont assignés
    Route::get('/mes-ordres', [OrdreFabricationController::class, 'mesOrdres'])->name('ordres.mes_ordres');
    
    // L'ouvrier déclare la fin de sa tâche
    Route::patch('/mes-ordres/{ordre}/statut', [OrdreFabricationController::class, 'updateStatutOuvrier'])->name('ordres.update_statut');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Niveau 2 : Gestion de Production (Chefs d'Atelier & Admins)
|--------------------------------------------------------------------------
| Zone réservée au pilotage de la chaîne de production et des stocks.
*/
Route::middleware(['auth', 'role:chef_atelier,admin'])->group(function () {
    
    // CRUD complet sur le catalogue des produits finis
    Route::resource('produits', ProduitController::class);

    // Gestion de l'infrastructure matérielle
    Route::resource('machines', MachineController::class);
    Route::patch('/machines/{machine}/statut', [MachineController::class, 'updateStatut'])->name('machines.statut');

    // Gestion de l'inventaire des ressources
    Route::resource('matieres', MatierePremiereController::class);
    Route::post('/matieres/{matiere}/ajuster', [MatierePremiereController::class, 'ajusterStock'])->name('matieres.ajuster');

    // Gestion complète des ordres de fabrication
    Route::resource('ordres', OrdreFabricationController::class)->except(['destroy']);
});


/*
|--------------------------------------------------------------------------
| Niveau 3 : Sécurité & Administration (Admins Uniquement)
|--------------------------------------------------------------------------
| Le sanctuaire du système.
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Suppression stricte d'un ordre (si autorisé par les règles métier)
    Route::delete('/ordres/{ordre}', [OrdreFabricationController::class, 'destroy'])->name('ordres.destroy');

    // Gestion des accès (RBAC)
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    // Registre de sécurité (Lecture seule absolue)
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('logs.index');
});

// Inclusion des routes générées par Laravel Breeze (Login, Register, Logout)
require __DIR__.'/auth.php';
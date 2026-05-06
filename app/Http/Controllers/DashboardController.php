<?php

namespace App\Http\Controllers;

use App\Models\OrdreFabrication;
use App\Models\Machine;
use App\Models\MatierePremiere;
use App\Models\Produit;
use App\Models\User;
use App\Models\AuditLog;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Statistiques des Ordres de Fabrication ---
        $totalOrdres       = OrdreFabrication::count();
        $ordresEnAttente   = OrdreFabrication::where('statut', 'en_attente')->count();
        $ordresEnCours     = OrdreFabrication::where('statut', 'en_cours')->count();
        $ordresTermines    = OrdreFabrication::where('statut', 'termine')->count();

        // --- Statistiques des Machines ---
        $totalMachines         = Machine::count();
        $machinesEnMarche      = Machine::where('statut', 'en_marche')->count();
        $machinesEnPanne       = Machine::where('statut', 'en_panne')->count();
        $machinesEnMaintenance = Machine::where('statut', 'en_maintenance')->count();

        // --- Alertes de Stock ---
        $matieresEnAlerte = MatierePremiere::whereColumn('quantite_stock', '<=', 'seuil_alerte')->get();

        // --- Compteurs généraux ---
        $totalProduits    = Produit::count();
        $totalUtilisateurs = User::count();

        // --- Activité récente (5 dernières actions) ---
        $dernieresActions = AuditLog::with('user')->latest()->take(5)->get();

        // --- 5 derniers ordres ---
        $derniersOrdres = OrdreFabrication::with(['produit', 'machine', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrdres', 'ordresEnAttente', 'ordresEnCours', 'ordresTermines',
            'totalMachines', 'machinesEnMarche', 'machinesEnPanne', 'machinesEnMaintenance',
            'matieresEnAlerte',
            'totalProduits', 'totalUtilisateurs',
            'dernieresActions', 'derniersOrdres'
        ));
    }
}

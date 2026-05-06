<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de Bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Message de bienvenue -->
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold dark:text-white">Bienvenue, {{ Auth::user()->name }} </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Rôle : <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->role->nom }}</span> — {{ now()->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if(in_array(Auth::user()->role->nom, ['admin', 'chef_atelier']))
                        <a href="{{ route('ordres.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            + Nouvel Ordre
                        </a>
                    @endif
                </div>
            </div>

            <!-- Cartes de statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Ordres -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordres de Fabrication</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrdres }}</div>
                        <div class="mt-2 flex space-x-3 text-xs">
                            <span class="text-gray-500">⏳ {{ $ordresEnAttente }} en attente</span>
                            <span class="text-yellow-600">🔄 {{ $ordresEnCours }} en cours</span>
                            <span class="text-green-600">✅ {{ $ordresTermines }} terminés</span>
                        </div>
                    </div>
                </div>

                <!-- Machines -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Parc Machines</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalMachines }}</div>
                        <div class="mt-2 flex space-x-3 text-xs">
                            <span class="text-green-600">🟢 {{ $machinesEnMarche }} en marche</span>
                            <span class="text-red-600">🔴 {{ $machinesEnPanne }} en panne</span>
                            <span class="text-yellow-600">🟡 {{ $machinesEnMaintenance }} maintenance</span>
                        </div>
                    </div>
                </div>

                <!-- Produits -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catalogue Produits</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProduits }}</div>
                        <div class="mt-2 text-xs text-gray-500">
                            <a href="{{ route('produits.index') }}" class="text-blue-600 hover:underline">Voir le catalogue →</a>
                        </div>
                    </div>
                </div>

                <!-- Utilisateurs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utilisateurs</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUtilisateurs }}</div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if(Auth::user()->role->nom === 'admin')
                                <a href="{{ route('users.index') }}" class="text-purple-600 hover:underline">Gérer les comptes →</a>
                            @else
                                Membres de l'équipe
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barres de progression des ordres -->
            @if($totalOrdres > 0)
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">Progression de la Production</h4>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden flex">
                        @if($ordresTermines > 0)
                            <div class="bg-green-500 h-4 transition-all duration-500" style="width: {{ ($ordresTermines / $totalOrdres) * 100 }}%"></div>
                        @endif
                        @if($ordresEnCours > 0)
                            <div class="bg-yellow-400 h-4 transition-all duration-500" style="width: {{ ($ordresEnCours / $totalOrdres) * 100 }}%"></div>
                        @endif
                        @if($ordresEnAttente > 0)
                            <div class="bg-gray-400 h-4 transition-all duration-500" style="width: {{ ($ordresEnAttente / $totalOrdres) * 100 }}%"></div>
                        @endif
                    </div>
                    <div class="mt-2 flex justify-between text-xs text-gray-500">
                        <span>🟢 Terminés : {{ $totalOrdres > 0 ? round(($ordresTermines / $totalOrdres) * 100) : 0 }}%</span>
                        <span>🟡 En cours : {{ $totalOrdres > 0 ? round(($ordresEnCours / $totalOrdres) * 100) : 0 }}%</span>
                        <span>⚪ En attente : {{ $totalOrdres > 0 ? round(($ordresEnAttente / $totalOrdres) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Alertes de Stock -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg {{ $matieresEnAlerte->count() > 0 ? 'border-l-4 border-red-500' : 'border-l-4 border-green-500' }}">
                    <div class="p-6">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 flex items-center">
                            @if($matieresEnAlerte->count() > 0)
                                <span class="text-red-600">⚠ Alertes de Stock ({{ $matieresEnAlerte->count() }})</span>
                            @else
                                <span class="text-green-600">✅ Stocks normaux</span>
                            @endif
                        </h4>
                        @if($matieresEnAlerte->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead>
                                    <tr>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Matière</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Stock</th>
                                        <th class="text-left text-xs font-medium text-gray-500 uppercase pb-2">Seuil</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($matieresEnAlerte as $matiere)
                                    <tr>
                                        <td class="py-2 font-bold text-red-700">{{ $matiere->nom }}</td>
                                        <td class="py-2 text-red-600 font-mono">{{ $matiere->quantite_stock }}</td>
                                        <td class="py-2 text-gray-500 font-mono">{{ $matiere->seuil_alerte }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-sm text-gray-500">Toutes les matières premières sont au-dessus de leur seuil d'alerte.</p>
                        @endif
                    </div>
                </div>

                <!-- Activité Récente -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-gray-400">
                    <div class="p-6">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">🕐 Activité Récente</h4>
                        @if($dernieresActions->count() > 0)
                            <ul class="space-y-3">
                                @foreach($dernieresActions as $log)
                                <li class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 w-2 h-2 mt-2 bg-indigo-500 rounded-full"></div>
                                    <div>
                                        <p class="text-sm text-gray-800 dark:text-gray-200">{{ $log->action }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user ? $log->user->name : 'Système' }} — {{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Aucune activité récente.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Derniers Ordres de Fabrication -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">📋 Derniers Ordres de Fabrication</h4>
                        <a href="{{ route('ordres.index') }}" class="text-xs text-indigo-600 hover:underline">Voir tout →</a>
                    </div>
                    @if($derniersOrdres->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tracking</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Machine</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assigné à</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($derniersOrdres as $ordre)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-3 font-mono text-gray-600">{{ substr($ordre->tracking_code, 0, 8) }}...</td>
                                        <td class="px-4 py-3 font-bold text-gray-900 dark:text-gray-100">{{ $ordre->produit->nom }} (x{{ $ordre->quantite }})</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $ordre->machine->nom }}</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $ordre->user->name }}</td>
                                        <td class="px-4 py-3">
                                            @if($ordre->statut === 'en_attente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">En attente</span>
                                            @elseif($ordre->statut === 'en_cours')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En cours</span>
                                            @elseif($ordre->statut === 'termine')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminé</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-400">{{ $ordre->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Aucun ordre de fabrication pour le moment.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

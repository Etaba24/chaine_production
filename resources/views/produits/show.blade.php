<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail du Produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-800">{{ $produit->nom }}</h3>
                        <p class="text-sm text-gray-500 font-mono">Réf: {{ $produit->reference }}</p>
                        <p class="text-sm text-gray-500 mt-1">Créé le {{ $produit->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    <h4 class="font-semibold text-gray-700 mb-2">Ordres de fabrication associés ({{ $produit->ordres->count() }})</h4>
                    @if($produit->ordres->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tracking</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($produit->ordres as $ordre)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-mono">{{ substr($ordre->tracking_code, 0, 8) }}...</td>
                                    <td class="px-4 py-2 text-sm">{{ $ordre->quantite }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($ordre->statut === 'en_attente')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">En attente</span>
                                        @elseif($ordre->statut === 'en_cours')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En cours</span>
                                        @elseif($ordre->statut === 'termine')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Terminé</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500">Aucun ordre de fabrication pour ce produit.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('produits.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Retour à la liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon Terminal d\'Atelier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @forelse($ordres as $ordre)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 {{ $ordre->statut === 'en_cours' ? 'border-yellow-500' : 'border-gray-400' }}">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ $ordre->produit->nom }}</h3>
                        <p class="text-sm text-gray-500 mb-4">Réf: {{ $ordre->produit->reference }} | Qte: {{ $ordre->quantite }}</p>
                        
                        <div class="mb-4">
                            <span class="text-xs font-semibold uppercase text-gray-600 tracking-wider">Machine assignée:</span>
                            <span class="block text-md font-bold text-indigo-600">{{ $ordre->machine->nom }}</span>
                        </div>

                        <!-- Actions de l'ouvrier -->
                        <form action="{{ route('ordres.update_statut', $ordre->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            @if($ordre->statut === 'en_attente')
                                <input type="hidden" name="statut" value="en_cours">
                                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded">
                                    Démarrer l'usinage
                                </button>
                            @elseif($ordre->statut === 'en_cours')
                                <input type="hidden" name="statut" value="termine">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                                    Déclarer Terminé
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-6 bg-white rounded-lg shadow text-center text-gray-500">
                    Aucun ordre de fabrication ne vous est assigné actuellement.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
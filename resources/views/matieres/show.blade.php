<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail de la Matière Première') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $matiere->nom }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Stock actuel</dt>
                            <dd class="text-sm text-gray-900">{{ $matiere->quantite_stock }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Seuil d'alerte</dt>
                            <dd class="text-sm text-gray-900">{{ $matiere->seuil_alerte }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">État</dt>
                            <dd>
                                @if($matiere->quantite_stock <= $matiere->seuil_alerte)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">⚠ Stock bas</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">OK</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                    <div class="mt-6">
                        <a href="{{ route('matieres.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Retour à l'inventaire</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

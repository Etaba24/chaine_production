<!-- resources/views/ordres/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Création d\'un Ordre de Fabrication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- SÉCURITÉ : Le jeton CSRF est obligatoire contre les attaques Cross-Site -->
                <form method="POST" action="{{ route('ordres.store') }}">
                    @csrf

                    <!-- Choix du Produit -->
                    <div class="mt-4">
                        <x-input-label for="produit_id" :value="__('Produit à fabriquer')" />
                        <select id="produit_id" name="produit_id" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Sélectionner un produit --</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>{{ $produit->reference }} - {{ $produit->nom }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('produit_id')" class="mt-2" />
                    </div>

                    <!-- Choix de la Machine -->
                    <div class="mt-4">
                        <x-input-label for="machine_id" :value="__('Machine assignée')" />
                        <select id="machine_id" name="machine_id" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Sélectionner une machine --</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{ $machine->nom }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('machine_id')" class="mt-2" />
                    </div>

                    <!-- Choix de l'Opérateur (Assignation) -->
                    <div class="mt-4">
                        <x-input-label for="user_id" :value="__('Assigner à (Opérateur)')" />
                        <select id="user_id" name="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Sélectionner un opérateur --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role->nom }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>

                    <!-- Quantité -->
                    <div class="mt-4">
                        <x-input-label for="quantite" :value="__('Quantité (unités)')" />
                        <x-text-input id="quantite" class="block mt-1 w-full dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600" type="number" name="quantite" min="1" value="{{ old('quantite') }}" required />
                        <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
                    </div>

                    <!-- Soumission -->
                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button>
                            {{ __('Générer l\'ordre et le QR Code') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
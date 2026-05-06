<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la Machine') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('machines.update', $machine) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de la machine</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $machine->nom) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="mb-4">
                            <label for="statut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                            <select name="statut" id="statut" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="en_marche" {{ old('statut', $machine->statut) == 'en_marche' ? 'selected' : '' }}>En marche</option>
                                <option value="en_panne" {{ old('statut', $machine->statut) == 'en_panne' ? 'selected' : '' }}>En panne</option>
                                <option value="en_maintenance" {{ old('statut', $machine->statut) == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('machines.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded">Annuler</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

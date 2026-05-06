<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail de l\'Utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <dl class="divide-y divide-gray-200">
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                            <dd class="text-sm text-gray-900">{{ $user->role ? $user->role->nom : 'Aucun' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Inscrit le</dt>
                            <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6">
                        <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

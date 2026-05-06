<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détail du Rôle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Rôle : {{ $role->nom }}</h3>

                    <h4 class="font-semibold text-gray-700 mb-2">Utilisateurs avec ce rôle ({{ $role->users->count() }})</h4>
                    @if($role->users->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($role->users as $user)
                                <tr>
                                    <td class="px-4 py-2 text-sm font-bold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $user->email }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-500">Aucun utilisateur avec ce rôle.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('roles.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

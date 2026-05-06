<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Suivi de Production - {{ $ordre->tracking_code }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mx-auto">
            <!-- Header avec Logo -->
            <div class="flex justify-center mb-8">
                <a href="/">
                    <x-application-logo class="w-16 h-16 text-indigo-600" />
                </a>
            </div>

            <!-- Carte Principale -->
            <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden">
                <!-- Bandeau supérieur -->
                <div class="bg-indigo-600 px-6 py-6 sm:px-10 text-white flex flex-col sm:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">Traçabilité Produit</h2>
                        <p class="text-indigo-200 mt-1">Code: <span class="font-mono text-white">{{ $ordre->tracking_code }}</span></p>
                    </div>
                    
                    <div class="mt-4 sm:mt-0">
                        @if($ordre->statut === 'en_attente')
                            <span class="px-4 py-2 rounded-full bg-white/20 text-white font-bold inline-flex items-center backdrop-blur-sm">
                                ⏳ En attente de production
                            </span>
                        @elseif($ordre->statut === 'en_cours')
                            <span class="px-4 py-2 rounded-full bg-yellow-400 text-yellow-900 font-bold inline-flex items-center shadow-lg animate-pulse">
                                🔄 En cours de fabrication
                            </span>
                        @elseif($ordre->statut === 'termine')
                            <span class="px-4 py-2 rounded-full bg-green-400 text-green-900 font-bold inline-flex items-center shadow-lg">
                                ✅ Fabrication terminée
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Détails -->
                <div class="px-6 py-8 sm:px-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Info Produit -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Informations Produit</h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mb-6">
                                <p class="text-xl font-bold text-gray-900">{{ $ordre->produit->nom }}</p>
                                <p class="text-sm text-gray-500 mt-1">Référence: {{ $ordre->produit->reference }}</p>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">Quantité produite:</p>
                                    <p class="text-2xl font-bold text-indigo-600">{{ $ordre->quantite }} <span class="text-base font-normal text-gray-500">unités</span></p>
                                </div>
                            </div>
                            
                            <!-- QR Code Block -->
                            <div class="flex flex-col items-center justify-center p-6 bg-white border-2 border-dashed border-gray-200 rounded-xl">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Code d'Authenticité</p>
                                <div class="p-3 bg-white shadow-sm border border-gray-100 rounded-lg">
                                    {!! QrCode::size(150)->style('round')->generate(route('trace.production', $ordre->tracking_code)) !!}
                                </div>
                                <p class="mt-3 text-xs text-gray-400 text-center">Flashez ce code pour retrouver<br>cette fiche d'identité</p>
                            </div>
                        </div>

                        <!-- Info Production -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Détails de Fabrication</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
                                        <span class="text-indigo-600 text-xs">🏭</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Ligne de production</p>
                                        <p class="text-sm text-gray-500">{{ $ordre->machine->nom }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
                                        <span class="text-indigo-600 text-xs">👨‍🔧</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Opérateur assigné</p>
                                        <p class="text-sm text-gray-500">{{ $ordre->user->name }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
                                        <span class="text-indigo-600 text-xs">📅</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Date de lancement</p>
                                        <p class="text-sm text-gray-500">{{ $ordre->created_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </li>
                                @if($ordre->statut === 'termine')
                                <li class="flex items-start">
                                    <div class="flex-shrink-0 h-6 w-6 rounded-full bg-green-100 flex items-center justify-center border border-green-200">
                                        <span class="text-green-600 text-xs">🏁</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Date d'achèvement</p>
                                        <p class="text-sm text-gray-500">{{ $ordre->updated_at->format('d/m/Y à H:i') }}</p>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Footer de la carte -->
                <div class="bg-gray-50 px-6 py-4 sm:px-10 border-t border-gray-100 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center">
                    <p class="text-xs text-gray-500">Document généré cryptographiquement par le système ERP Industriel.</p>
                    <a href="/" class="mt-2 sm:mt-0 text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">Retour à l'accueil</a>
                </div>
            </div>
            
        </div>
    </body>
</html>

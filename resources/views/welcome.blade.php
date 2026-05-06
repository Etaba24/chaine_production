<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP Industriel - Contrôle d'Accès</title>
    <!-- Chargement de Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900 selection:bg-indigo-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">
        
        <!-- Motif de fond technique (Optionnel, donne un style industriel) -->
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 24px 24px;"></div>

        <!-- Navigation Supérieure Droite -->
        <div class="absolute top-0 right-0 p-6 z-10">
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Connexion Sécurisée</a>
                    @endauth
                </div>
            @endif
        </div>

        <!-- Contenu Central -->
        <div class="relative z-10 max-w-4xl w-full px-6 text-center">
            
            <!-- Icône Industrielle / Logo -->
            <div class="mb-8 flex justify-center">
                <div class="p-4 bg-indigo-100 rounded-full">
                    <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 mb-4">
                Système de Gestion de Production
            </h1>
            
            <p class="text-xl text-gray-500 mb-10 max-w-2xl mx-auto">
                Interface centralisée pour la traçabilité des chaînes de montage, la gestion des ordres de fabrication et le pilotage SCADA.
            </p>

            <!-- Bouton d'Action Principal (Call To Action) -->
            <div class="flex justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-transform transform hover:-translate-y-1">
                        Accéder à mon espace
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-transform transform hover:-translate-y-1">
                        S'authentifier
                    </a>
                @endauth
            </div>
        </div>

        <!-- Pied de page -->
        <div class="absolute bottom-0 w-full p-6 text-center text-sm text-gray-400 z-10">
            &copy; {{ date('Y') }} Infrastructure de Production - Accès Restreint et Surveillé.
        </div>
    </div>
</body>
</html>
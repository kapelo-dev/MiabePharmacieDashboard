<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Miabe Pharmacie - Tableau de bord</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen bg-gradient-to-b from-gray-100 to-white dark:from-gray-900 dark:to-gray-800">
            <div class="relative pt-6 pb-16 sm:pb-24">
                <main class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24">
                    <div class="text-center">
                        <!-- Logo -->
                        <div class="flex justify-center mb-8">
                            <svg class="w-20 h-20 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                            <span class="block">Bienvenue sur</span>
                            <span class="block text-green-600 dark:text-green-500">Miabe Pharmacie</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            Optimisez la gestion de votre pharmacie avec notre solution complète. Simplifiez vos opérations quotidiennes, suivez vos stocks en temps réel et prenez des décisions éclairées grâce à nos outils d'analyse avancés.
                        </p>
                        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
            @if (Route::has('login'))
                                <div class="rounded-md shadow">
                    @auth
                                        <a href="{{ url('/dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                            Accéder au tableau de bord
                        </a>
                    @else
                                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                            Se connecter
                                        </a>
                                    @endauth
                                </div>
                        @if (Route::has('register'))
                                    <div class="mt-3 sm:mt-0 sm:ml-3">
                                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                            Créer un compte
                                        </a>
                                    </div>
                        @endif
            @endif
                        </div>
                    </div>
                </main>
            </div>

            <!-- Features section -->
            <div class="py-12 bg-white dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-green-600 dark:text-green-500 font-semibold tracking-wide uppercase">Fonctionnalités</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                            Tout ce dont vous avez besoin pour gérer votre pharmacie
                        </p>
                        <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                            Une solution complète et intuitive pour optimiser la gestion de votre établissement.
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                            <!-- Feature 1 -->
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Gestion des stocks</p>
                                <div class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                    Suivez vos stocks en temps réel et recevez des alertes pour les réapprovisionnements.
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Analyse des ventes</p>
                                <div class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                    Visualisez vos performances de vente avec des graphiques détaillés.
                                </div>
                </div>

                            <!-- Feature 3 -->
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Gestion des clients</p>
                                <div class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                    Gardez une trace de vos clients et de leurs historiques d'achats.
                                </div>
                            </div>

                            <!-- Feature 4 -->
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Rappels et notifications</p>
                                <div class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                                    Recevez des alertes pour les dates d'expiration et les stocks bas.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <aside class="w-56 bg-white shadow-2xl flex flex-col py-6 px-4 rounded-r-2xl">
                <div class="flex items-center mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-300 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" fill="#3b82f6"/>
                            <rect x="7" y="10" width="10" height="7" rx="2" fill="#fff"/>
                            <rect x="10.5" y="12" width="3" height="5" rx="1" fill="#3b82f6"/>
                            <rect x="11.5" y="13.5" width="1" height="2" rx="0.5" fill="#fff"/>
                        </svg>
                    </div>
                    <span class="ml-3 font-extrabold text-lg text-blue-700 tracking-wide">Dompet RS</span>
                </div>
                <nav class="flex-1">
                    <ul class="space-y-2">
                        <li><a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">🏥</span> Dashboard</a></li>
                        <li><a href="#" class="flex items-center px-3 py-2 rounded-lg transition hover:bg-blue-50 text-gray-700 font-semibold"><span class="mr-2">💳</span> Piutang</a></li>
                        <li><a href="#" class="flex items-center px-3 py-2 rounded-lg transition hover:bg-blue-50 text-gray-700 font-semibold"><span class="mr-2">📊</span> Statistik</a></li>
                        <li><a href="{{ route('pasien-rawat-inap') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('pasien-rawat-inap') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">🧑‍⚕️</span> Rawat Inap</a></li>
                        <li><a href="{{ route('rawat-jalan') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('rawat-jalan') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">🧑‍⚕️</span> Rawat Jalan</a></li>
                        <li><a href="{{ route('klaim-kompilasi') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('klaim-kompilasi') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">📁</span> Kompilasi Berkas Klaim BPJS</a></li>
                        <li><a href="{{ route('skrining-mpp') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('skrining-mpp') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">🩺</span> Skrining Manager Pelayanan Pasien (MPP)</a></li>
                        <li><a href="{{ route('monitoring-klaim-bpjs') }}" class="flex items-center px-3 py-2 rounded-lg transition {{ request()->routeIs('monitoring-klaim-bpjs') ? 'bg-blue-100 text-blue-700 font-bold shadow' : 'hover:bg-blue-50 text-gray-700 font-semibold' }}"><span class="mr-2">📝</span> Monitoring Klaim BPJS</a></li>
                    </ul>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-lg transition hover:bg-red-100 text-red-700 font-bold">
                                <span class="mr-2">🚪</span> Logout
                            </button>
                        </form>
                    </li>
                </nav>
                <div class="mt-8 flex items-center">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-8 h-8 rounded-full shadow-lg" alt="User">
                    <div class="ml-3">
                        <div class="font-bold text-gray-800 text-sm">Fanny Faqih Subekti</div>
                        <div class="text-xs text-gray-500">Admin</div>
                    </div>
                </div>
            </aside>
            <!-- Page Content -->
            <main class="flex-1 p-0">
                @yield('content')
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        @yield('scripts')
    </body>
</html>

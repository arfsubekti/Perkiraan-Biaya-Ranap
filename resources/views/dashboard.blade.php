@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex">
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-extrabold text-gray-800 tracking-wide">Dashboard Rawat Inap</h1>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Cari pasien..." class="px-4 py-1.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 shadow transition w-48 text-sm">
                        <span class="absolute right-3 top-2 text-blue-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
                    </div>
                    <button class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-4 py-1.5 rounded-lg font-bold shadow-lg hover:scale-105 transition text-sm">Cetak Laporan</button>
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="w-8 h-8 rounded-full shadow-lg" alt="User">
                </div>
            </div>
            <!-- Cards & Chart -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="relative group bg-gradient-to-br from-orange-400 to-orange-200 rounded-xl p-4 flex flex-col items-center shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                    <div class="mb-1 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z"/><path d="M12 7v5l3 3" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-white mb-1">2478</div>
                    <div class="text-xs text-white font-semibold">Total Pasien</div>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-30 rounded-full px-2 py-0.5 text-xs text-orange-900 font-bold shadow">+5%</div>
                </div>
                <div class="relative group bg-gradient-to-br from-green-400 to-green-200 rounded-xl p-4 flex flex-col items-center shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                    <div class="mb-1 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="#fff"/><path d="M12 8v4l3 3" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-white mb-1">983</div>
                    <div class="text-xs text-white font-semibold">Pasien Aktif</div>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-30 rounded-full px-2 py-0.5 text-xs text-green-900 font-bold shadow">+2%</div>
                </div>
                <div class="relative group bg-gradient-to-br from-purple-400 to-purple-200 rounded-xl p-4 flex flex-col items-center shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                    <div class="mb-1 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="8" ry="6" fill="#fff"/><path d="M12 10v4l3 3" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-white mb-1">1256</div>
                    <div class="text-xs text-white font-semibold">Pasien Pulang</div>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-30 rounded-full px-2 py-0.5 text-xs text-purple-900 font-bold shadow">+3%</div>
                </div>
                <div class="relative group bg-gradient-to-br from-blue-400 to-blue-200 rounded-xl p-4 flex flex-col items-center shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer">
                    <div class="mb-1 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="text-2xl font-extrabold text-white mb-1">652</div>
                    <div class="text-xs text-white font-semibold">Total Piutang</div>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-30 rounded-full px-2 py-0.5 text-xs text-blue-900 font-bold shadow">+1%</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-700 mb-2 text-sm">Statistik Pasien</h3>
                    <canvas id="pasienChart" class="w-full h-full" style="max-width:320px;max-height:320px;"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-700 mb-2 text-sm">Aktivitas</h3>
                    <canvas id="aktivitasChart" class="w-full h-full" style="max-width:320px;max-height:320px;"></canvas>
                </div>
            </div>
            <!-- Chart.js Script -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            // Statistik Pasien
            var ctxPasien = document.getElementById('pasienChart').getContext('2d');
            new Chart(ctxPasien, {
                type: 'pie',
                data: {
                    labels: ['Aktif', 'Pulang', 'Piutang'],
                    datasets: [{
                        data: [983, 1256, 652],
                        backgroundColor: ['#34d399', '#a78bfa', '#60a5fa'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
            // Aktivitas
            var ctxAktivitas = document.getElementById('aktivitasChart').getContext('2d');
            new Chart(ctxAktivitas, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                    datasets: [{
                        label: 'Aktivitas',
                        data: [12, 19, 7, 14, 9],
                        backgroundColor: '#34d399',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
            </script>
        </main>
    </div>
@endsection

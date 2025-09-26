@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex flex-col items-center py-10">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-7xl mb-8">
        <h2 class="text-2xl font-bold text-blue-700 mb-6">Monitoring Verifikasi SEP BPJS</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-base rounded-lg overflow-hidden">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Tanggal SEP</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Jenis Pelayanan</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Status</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">No SEP</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Status Klaim</th>
                        <th class="px-3 py-2 text-right font-bold text-blue-700">Biaya Pengajuan</th>
                        <th class="px-3 py-2 text-right font-bold text-blue-700">Biaya Disetujui</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i=0; $i<20; $i++)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="px-3 py-1">{{ date('Y-m-d', strtotime('-'.$i.' days')) }}</td>
                        <td class="px-3 py-1">{{ ['Rawat Inap','Rawat Jalan'][rand(0,1)] }}</td>
                        <td class="px-3 py-1">{{ ['Proses','Selesai','Ditolak'][rand(0,2)] }}</td>
                        <td class="px-3 py-1">SEP{{ rand(100000,999999) }}</td>
                        <td class="px-3 py-1">{{ ['Verifikasi','Belum Verifikasi','Klaim'][rand(0,2)] }}</td>
                        <td class="px-3 py-1 text-right">{{ number_format(rand(1000000,5000000)) }}</td>
                        <td class="px-3 py-1 text-right">{{ number_format(rand(1000000,5000000)) }}</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

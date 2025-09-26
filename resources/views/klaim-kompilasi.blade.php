@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex flex-col ml-6">
    <div class="bg-white rounded-xl shadow-xl p-4 w-full mb-8">
        <h2 class="text-lg font-bold text-blue-700 mb-4">Kompilasi Berkas Klaim BPJS</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-base rounded-lg overflow-hidden">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">No Rawat</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">No SEP</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">No RM</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Nama Pasien</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Status Lanjut</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Tgl Registrasi</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Tgl Pulang</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Status Pulang</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Ruangan</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Dokter</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Diagnosa</th>
                        <th class="px-3 py-2 text-left font-bold text-blue-700">Status Kirim</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($klaim as $k)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="px-3 py-1">{{ $k->no_rawat }}</td>
                        <td class="px-3 py-1">{{ $k->no_sep }}</td>
                        <td class="px-3 py-1">{{ $k->no_rkm_medis }}</td>
                        <td class="px-3 py-1">{{ $k->nm_pasien }}</td>
                        <td class="px-3 py-1">{{ $k->status_lanjut }}</td>
                        <td class="px-3 py-1">{{ $k->tgl_registrasi }}</td>
                        <td class="px-3 py-1">{{ $k->tglpulang }}</td>
                        <td class="px-3 py-1">{{ $k->stts_pulang }}</td>
                        <td class="px-3 py-1">{{ $k->ruangan }}</td>
                        <td class="px-3 py-1">{{ $k->nm_dokter }}</td>
                        <td class="px-3 py-1">{{ $k->kd_penyakit }}</td>
                        <td class="px-3 py-1">
                            <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $k->inacbg_terkirim ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $k->inacbg_terkirim ? 'Terkirim' : 'Belum Terkirim' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            {{ $klaim->links() }}
        </div>
    </div>
</div>
@endsection

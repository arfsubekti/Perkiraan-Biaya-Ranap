@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex flex-col ml-6">
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-xl p-4">
            <h2 class="text-lg font-bold text-blue-700 mb-4">Daftar Pasien Rawat Jalan BPJS</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-base rounded-lg overflow-hidden">
                    <thead class="bg-blue-100">
                        <tr>
                            <th class="px-3 py-2">No Rawat</th>
                            <th class="px-3 py-2">Nama Pasien</th>
                            <th class="px-3 py-2">Umur</th>
                            <th class="px-3 py-2">Poli</th>
                            <th class="px-3 py-2">Dokter</th>
                            <th class="px-3 py-2">Tgl Registrasi</th>
                            <th class="px-3 py-2">Jam</th>
                            <th class="px-3 py-2">Penanggung Jawab</th>
                            <th class="px-3 py-2">Alamat PJ</th>
                            <th class="px-3 py-2">Hubungan PJ</th>
                            <th class="px-3 py-2">Biaya RS</th>
                            <th class="px-3 py-2">Status Bayar</th>
                            <th class="px-3 py-2">Status Poli</th>
                            <th class="px-3 py-2">Status Berkas</th>
                            <th class="px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pasien as $p)
                        <tr>
                            <td class="px-3 py-1">{{ $p->no_rawat }}</td>
                            <td class="px-3 py-1">{{ $p->nm_pasien }}</td>
                            <td class="px-3 py-1">{{ $p->umur }}</td>
                            <td class="px-3 py-1">{{ $p->nm_poli }}</td>
                            <td class="px-3 py-1">{{ $p->nm_dokter }}</td>
                            <td class="px-3 py-1">{{ $p->tgl_registrasi }}</td>
                            <td class="px-3 py-1">{{ $p->jam_reg }}</td>
                            <td class="px-3 py-1">{{ $p->p_jawab }}</td>
                            <td class="px-3 py-1">{{ $p->almt_pj }}</td>
                            <td class="px-3 py-1">{{ $p->hubunganpj }}</td>
                            <td class="px-3 py-1">{{ number_format($p->biaya_rs,0,',','.') }}</td>
                            <td class="px-3 py-1">{{ $p->status_bayar }}</td>
                            <td class="px-3 py-1">{{ $p->status_poli }}</td>
                            <td class="px-3 py-1">{{ $p->status_berkas ?? '-' }}</td>
                            <td class="px-3 py-1">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-bold shadow" onclick="showModalDetail('{{ $p->no_rawat }}', '{{ $p->nm_pasien }}')">Detail Berkas</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-center">
                {{ $pasien->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
    <div id="modalDetailBerkas" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 hidden">
        <div class="bg-gradient-to-br from-blue-50 via-white to-blue-100 rounded-xl shadow-xl p-4 w-96 max-w-full border border-blue-200 flex flex-col" style="max-height:70vh;">
            <div class="flex items-center mb-3">
                <svg class="w-6 h-6 text-blue-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20h.01M12 4h.01"/></svg>
                <h3 class="text-base font-bold text-blue-700 tracking-wide" id="modalTitle">Status Dokumen Pasien</h3>
            </div>
            <div id="modalContent" class="overflow-y-auto flex-1 pr-1" style="max-height:45vh;"></div>
            <div class="pt-3 pb-1 flex justify-end sticky bottom-0">
                <button onclick="closeModalDetail()" class="bg-gradient-to-r from-blue-400 to-blue-600 hover:from-blue-500 hover:to-blue-700 text-white px-4 py-1 rounded-lg font-semibold shadow transition-all duration-150">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function showModalDetail(no_rawat, nama_pasien) {
    document.getElementById('modalDetailBerkas').classList.remove('hidden');
    document.getElementById('modalDetailBerkas').classList.add('flex');
    document.getElementById('modalContent').innerHTML = '<div class="text-blue-700 font-bold mb-2">'+nama_pasien+'</div><div>Loading...</div>';
    fetch('/rawat-jalan/detail-status-berkas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({no_rawat: no_rawat})
    })
    .then(res => res.json())
    .then(data => {
        let html = `<div class='mb-2 text-base font-bold text-blue-700'>${nama_pasien}</div>`;
        html += `<table class='w-full text-xs border border-blue-100 rounded-lg overflow-hidden font-sans'><thead><tr class='bg-blue-100 text-blue-700 font-semibold'><th class='text-left px-3 py-2'>Dokumen</th><th class='text-center px-3 py-2'>Status</th></tr></thead><tbody>`;
        let i = 0;
        Object.entries(data).forEach(([dok, status]) => {
            html += `<tr class='${i%2===0?'bg-white':'bg-blue-50'}'>`;
            html += `<td class='px-3 py-2 border-b border-blue-50'>${dok}</td><td class='px-3 py-2 border-b border-blue-50 text-center'>`;
            if(status==='Ada') {
                html += `<span class='inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded font-bold'><svg class='w-3 h-3 mr-1 text-green-500' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/></svg>Ada</span>`;
            } else {
                html += `<span class='inline-flex items-center justify-center px-2 py-1 bg-red-100 text-red-600 rounded font-bold gap-1'><svg class='w-4 h-4 text-red-500' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><circle cx='12' cy='12' r='10' fill='#fee2e2'/><path stroke-linecap='round' stroke-linejoin='round' d='M6 18L18 6M6 6l12 12' stroke='#ef4444' stroke-width='2'/></svg><span>Tidak Ada</span></span>`;
            }
            html += `</td></tr>`;
            i++;
        });
        html += '</tbody></table>';
        document.getElementById('modalContent').innerHTML = html;
    });
}
function closeModalDetail() {
    document.getElementById('modalDetailBerkas').classList.add('hidden');
    document.getElementById('modalDetailBerkas').classList.remove('flex');
}
</script>
@endsection

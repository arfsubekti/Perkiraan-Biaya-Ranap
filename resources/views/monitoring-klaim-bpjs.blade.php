@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex flex-col">
    <div class="bg-white rounded-xl shadow-xl p-4 w-full max-w-6xl ml-4 mb-8">
        <h2 class="text-lg font-bold text-blue-700 mb-4">Monitoring Klaim BPJS</h2>
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <label class="text-xs">Tgl SEP:</label>
            <input type="date" name="tgl1" value="{{ $tgl1 }}" class="border rounded px-2 py-1" />
            <span class="mx-1">s.d.</span>
            <input type="date" name="tgl2" value="{{ $tgl2 }}" class="border rounded px-2 py-1" />
            <select name="kelas" class="border rounded px-2 py-1">
                <option value="Semua" {{ $kelas=='Semua'?'selected':'' }}>Semua</option>
                <option value="1" {{ $kelas=='1'?'selected':'' }}>Kelas 1</option>
                <option value="2" {{ $kelas=='2'?'selected':'' }}>Kelas 2</option>
                <option value="3" {{ $kelas=='3'?'selected':'' }}>Kelas 3</option>
            </select>
            <select name="status" class="border rounded px-2 py-1">
                <option value="Semua" {{ $status=='Semua'?'selected':'' }}>Semua</option>
                <option value="1" {{ $status=='1'?'selected':'' }}>Proses Verifikasi</option>
                <option value="2" {{ $status=='2'?'selected':'' }}>Pending Verifikasi</option>
                <option value="3" {{ $status=='3'?'selected':'' }}>Klaim</option>
            </select>
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari SEP/Nama Pasien..." class="border rounded px-2 py-1" />
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>
        <div class="overflow-auto">
        <table class="min-w-full bg-white border text-xs">
            <thead>
                <tr class="bg-blue-100 text-blue-700">
                    <th class="border px-2 py-1">No.SEP</th>
                    <th class="border px-2 py-1">No.Rawat</th>
                    <th class="border px-2 py-1">No.RM</th>
                    <th class="border px-2 py-1">Nama Pasien</th>
                    <th class="border px-2 py-1">Tgl SEP</th>
                    <th class="border px-2 py-1">Tgl Rujukan</th>
                    <th class="border px-2 py-1">No.Rujukan</th>
                    <th class="border px-2 py-1">Kode PPK Rujukan</th>
                    <th class="border px-2 py-1">Nama PPK Rujukan</th>
                    <th class="border px-2 py-1">Kode PPK Pelayanan</th>
                    <th class="border px-2 py-1">Nama PPK Pelayanan</th>
                    <th class="border px-2 py-1">Jenis Pelayanan</th>
                    <th class="border px-2 py-1">Catatan</th>
                    <th class="border px-2 py-1">Kode Diagnosa</th>
                    <th class="border px-2 py-1">Nama Diagnosa</th>
                    <th class="border px-2 py-1">Kode Poli</th>
                    <th class="border px-2 py-1">Nama Poli</th>
                    <th class="border px-2 py-1">Kelas Rawat</th>
                    <th class="border px-2 py-1">Laka Lantas</th>
                    <th class="border px-2 py-1">Lokasi Laka Lantas</th>
                    <th class="border px-2 py-1">User Input</th>
                    <th class="border px-2 py-1">Tgl Lahir</th>
                    <th class="border px-2 py-1">Peserta</th>
                    <th class="border px-2 py-1">J.Kel</th>
                    <th class="border px-2 py-1">No.Kartu</th>
                    <th class="border px-2 py-1">Tgl Pulang</th>
                    <th class="border px-2 py-1">Asal Rujukan</th>
                    <th class="border px-2 py-1">Eksekutif</th>
                    <th class="border px-2 py-1">COB</th>
                    <th class="border px-2 py-1">Penjamin</th>
                    <th class="border px-2 py-1">No.Telp</th>
                    <th class="border px-2 py-1">INACBG</th>
                    <th class="border px-2 py-1">No.FPK</th>
                    <th class="border px-2 py-1">Pengajuan</th>
                    <th class="border px-2 py-1">Disetujui</th>
                    <th class="border px-2 py-1">Tarif Gruper</th>
                    <th class="border px-2 py-1">Tarif RS</th>
                    <th class="border px-2 py-1">Topup</th>
                    <th class="border px-2 py-1">Untung/Rugi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td class="border px-2 py-1">{{ $row->no_sep }}</td>
                    <td class="border px-2 py-1">{{ $row->no_rawat }}</td>
                    <td class="border px-2 py-1">{{ $row->no_rm }}</td>
                    <td class="border px-2 py-1">{{ $row->nama_pasien }}</td>
                    <td class="border px-2 py-1">{{ $row->tglsep }}</td>
                    <td class="border px-2 py-1">{{ $row->tglrujukan }}</td>
                    <td class="border px-2 py-1">{{ $row->no_rujukan }}</td>
                    <td class="border px-2 py-1">{{ $row->kdppkrujukan }}</td>
                    <td class="border px-2 py-1">{{ $row->nmppkrujukan }}</td>
                    <td class="border px-2 py-1">{{ $row->kdppkpelayanan }}</td>
                    <td class="border px-2 py-1">{{ $row->nmppkpelayanan }}</td>
                    <td class="border px-2 py-1">{{ $row->jns_pelayanan }}</td>
                    <td class="border px-2 py-1">{{ $row->catatan }}</td>
                    <td class="border px-2 py-1">{{ $row->kd_diagnosaawal }}</td>
                    <td class="border px-2 py-1">{{ $row->nmdiagnosaawal }}</td>
                    <td class="border px-2 py-1">{{ $row->kd_poli }}</td>
                    <td class="border px-2 py-1">{{ $row->nm_poli }}</td>
                    <td class="border px-2 py-1">{{ $row->kelas_rawat }}</td>
                    <td class="border px-2 py-1">{{ $row->lakalantas }}</td>
                    <td class="border px-2 py-1">{{ $row->user }}</td>
                    <td class="border px-2 py-1">{{ $row->tgl_lahir }}</td>
                    <td class="border px-2 py-1">{{ $row->peserta }}</td>
                    <td class="border px-2 py-1">{{ $row->jk }}</td>
                    <td class="border px-2 py-1">{{ $row->no_kartu }}</td>
                    <td class="border px-2 py-1">{{ $row->tglpulang }}</td>
                    <td class="border px-2 py-1">{{ $row->asal_rujukan }}</td>
                    <td class="border px-2 py-1">{{ $row->eksekutif }}</td>
                    <td class="border px-2 py-1">{{ $row->cob }}</td>
                    <td class="border px-2 py-1">{{ $row->no_telp }}</td>
                    <td class="border px-2 py-1">{{ $row->inacbg }}</td>
                    <td class="border px-2 py-1">{{ $row->no_fpk }}</td>
                    <td class="border px-2 py-1">{{ $row->pengajuan }}</td>
                    <td class="border px-2 py-1">{{ $row->disetujui }}</td>
                    <td class="border px-2 py-1">{{ $row->tarif_gruper }}</td>
                    <td class="border px-2 py-1">{{ $row->tarif_rs }}</td>
                    <td class="border px-2 py-1">{{ $row->topup }}</td>
                    <td class="border px-2 py-1">{{ $row->untung_rugi }}</td>
                </tr>
                @empty
                <tr><td colspan="40" class="text-center py-4 text-gray-500">Tidak ada data klaim BPJS.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <div class="mt-4 flex justify-center">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection

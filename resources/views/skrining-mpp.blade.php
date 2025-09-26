@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-300 flex flex-col ml-6">
    <div class="bg-white rounded-xl shadow-xl p-4 w-full mb-8">
        <h2 class="text-lg font-bold text-blue-700 mb-4">Skrining Manager Pelayanan Pasien (MPP)</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
        @endif
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari No Rawat/Nama Pasien..." class="border rounded px-2 py-1" />
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Cari</button>
        </form>
        <table class="min-w-full bg-white border">
            <thead>
                <tr class="bg-blue-100 text-blue-700 text-xs">
                    <th class="border px-2 py-1">No Rawat</th>
                    <th class="border px-2 py-1">No RM</th>
                    <th class="border px-2 py-1">Nama Pasien</th>
                    <th class="border px-2 py-1">JK</th>
                    <th class="border px-2 py-1">Tgl Lahir</th>
                    <th class="border px-2 py-1">Alamat</th>
                    <th class="border px-2 py-1">Tgl Skrining</th>
                    <th class="border px-2 py-1">Param 1</th>
                    <th class="border px-2 py-1">Param 2</th>
                    <th class="border px-2 py-1">Param 3</th>
                    <th class="border px-2 py-1">Param 4</th>
                    <th class="border px-2 py-1">Param 5</th>
                    <th class="border px-2 py-1">Param 6</th>
                    <th class="border px-2 py-1">Param 7</th>
                    <th class="border px-2 py-1">Param 8</th>
                    <th class="border px-2 py-1">Param 9</th>
                    <th class="border px-2 py-1">Param 10</th>
                    <th class="border px-2 py-1">Param 11</th>
                    <th class="border px-2 py-1">Param 12</th>
                    <th class="border px-2 py-1">Param 13</th>
                    <th class="border px-2 py-1">Param 14</th>
                    <th class="border px-2 py-1">Param 15</th>
                    <th class="border px-2 py-1">Param 16</th>
                    <th class="border px-2 py-1">NIP</th>
                    <th class="border px-2 py-1">Nama Petugas</th>
                    <th class="border px-2 py-1">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr class="text-xs">
                    <td class="border px-2 py-1">{{ $row->no_rawat }}</td>
                    <td class="border px-2 py-1">{{ $row->no_rm }}</td>
                    <td class="border px-2 py-1">{{ $row->nama_pasien }}</td>
                    <td class="border px-2 py-1">{{ $row->jk }}</td>
                    <td class="border px-2 py-1">{{ $row->tgl_lahir }}</td>
                    <td class="border px-2 py-1">{{ $row->alamat }}</td>
                    <td class="border px-2 py-1">{{ $row->tgl_skrining }}</td>
                    <td class="border px-2 py-1">{{ $row->param1 }}</td>
                    <td class="border px-2 py-1">{{ $row->param2 }}</td>
                    <td class="border px-2 py-1">{{ $row->param3 }}</td>
                    <td class="border px-2 py-1">{{ $row->param4 }}</td>
                    <td class="border px-2 py-1">{{ $row->param5 }}</td>
                    <td class="border px-2 py-1">{{ $row->param6 }}</td>
                    <td class="border px-2 py-1">{{ $row->param7 }}</td>
                    <td class="border px-2 py-1">{{ $row->param8 }}</td>
                    <td class="border px-2 py-1">{{ $row->param9 }}</td>
                    <td class="border px-2 py-1">{{ $row->param10 }}</td>
                    <td class="border px-2 py-1">{{ $row->param11 }}</td>
                    <td class="border px-2 py-1">{{ $row->param12 }}</td>
                    <td class="border px-2 py-1">{{ $row->param13 }}</td>
                    <td class="border px-2 py-1">{{ $row->param14 }}</td>
                    <td class="border px-2 py-1">{{ $row->param15 }}</td>
                    <td class="border px-2 py-1">{{ $row->param16 }}</td>
                    <td class="border px-2 py-1">{{ $row->nip }}</td>
                    <td class="border px-2 py-1">{{ $row->nama_petugas }}</td>
                    <td class="border px-2 py-1 flex gap-1">
                        <form method="POST" action="{{ route('skrining-mpp.destroy', $row->no_rawat) }}" onsubmit="return confirm('Hapus data?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                        </form>
                        <a href="#" onclick="editMPP('{{ $row->no_rawat }}')" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="27" class="text-center py-4 text-gray-500">Tidak ada data skrining MPP.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 flex justify-center">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection

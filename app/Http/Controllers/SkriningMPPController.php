<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterMasalahMPP;
class SkriningMPPController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $data = MasterMasalahMPP::where('kode_masalah', 'like', "%$keyword%")
            ->orWhere('nama_masalah', 'like', "%$keyword%")
            ->orderBy('kode_masalah')
            ->paginate(10);
        return view('skrining-mpp', compact('data', 'keyword'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode_masalah' => 'required|max:3',
            'nama_masalah' => 'required|max:100',
        ]);
        MasterMasalahMPP::create($request->only(['kode_masalah', 'nama_masalah']));
        return redirect()->route('skrining-mpp')->with('success', 'Data berhasil disimpan');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_masalah' => 'required|max:3',
            'nama_masalah' => 'required|max:100',
        ]);
        $masalah = MasterMasalahMPP::findOrFail($id);
        $masalah->update($request->only(['kode_masalah', 'nama_masalah']));
        return redirect()->route('skrining-mpp')->with('success', 'Data berhasil diubah');
    }
    public function destroy($id)
    {
        MasterMasalahMPP::destroy($id);
        return redirect()->route('skrining-mpp')->with('success', 'Data berhasil dihapus');
    }
}

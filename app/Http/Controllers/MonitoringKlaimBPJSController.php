<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringKlaimBPJSController extends Controller
{
    public function index(Request $request)
    {
        $tgl1 = $request->input('tgl1', date('Y-m-01'));
        $tgl2 = $request->input('tgl2', date('Y-m-d'));
        $jenis_pelayanan = $request->input('jenis_pelayanan', 'Semua');
        $kelas = $request->input('kelas', 'Semua');
        $status = $request->input('status', 'Semua');
        $keyword = $request->input('keyword', '');

        $query = DB::table('bridging_sep')
            ->leftJoin('reg_periksa', 'bridging_sep.no_rawat', '=', 'reg_periksa.no_rawat')
            ->select([
                'bridging_sep.no_sep',
                'bridging_sep.no_rawat',
                'bridging_sep.nomr as no_rm',
                'bridging_sep.nama_pasien',
                'bridging_sep.tglsep',
                'bridging_sep.tglrujukan',
                'bridging_sep.no_rujukan',
                'bridging_sep.kdppkrujukan',
                'bridging_sep.nmppkrujukan',
                'bridging_sep.kdppkpelayanan',
                'bridging_sep.nmppkpelayanan',
                'bridging_sep.jnspelayanan as jns_pelayanan',
                'bridging_sep.catatan',
                'bridging_sep.diagawal as kd_diagnosaawal',
                'bridging_sep.nmdiagnosaawal',
                'bridging_sep.kdpolitujuan as kd_poli',
                'bridging_sep.nmpolitujuan as nm_poli',
                'bridging_sep.klsrawat as kelas_rawat',
                'bridging_sep.lakalantas',
                'bridging_sep.user',
                'bridging_sep.tanggal_lahir as tgl_lahir',
                'bridging_sep.peserta',
                'bridging_sep.jkel as jk',
                'bridging_sep.no_kartu',
                'bridging_sep.tglpulang',
                'bridging_sep.asal_rujukan',
                'bridging_sep.eksekutif',
                'bridging_sep.cob',
                'bridging_sep.notelep as no_telp',
                DB::raw('"" as inacbg'),
                DB::raw('"" as no_fpk'),
                DB::raw('"" as pengajuan'),
                DB::raw('"" as disetujui'),
                DB::raw('"" as tarif_gruper'),
                DB::raw('"" as tarif_rs'),
                DB::raw('"" as topup'),
                DB::raw('"" as untung_rugi')
            ])
            ->whereBetween('bridging_sep.tglsep', [$tgl1, $tgl2]);

        if ($kelas !== 'Semua') {
            $query->where('bridging_sep.kelas_rawat', $kelas);
        }
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('bridging_sep.no_sep', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.no_rawat', 'like', "%$keyword%")
                  ->orWhere('pasien.no_rkm_medis', 'like', "%$keyword%")
                  ->orWhere('pasien.nm_pasien', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.nmppkrujukan', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.nmppkpelayanan', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.nm_diagnosaawal', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.nm_poli', 'like', "%$keyword%")
                  ->orWhere('bridging_sep.no_fpk', 'like', "%$keyword%")
                  ;
            });
        }
        $data = $query->orderBy('bridging_sep.tglsep', 'desc')->paginate(8);

        return view('monitoring-klaim-bpjs', compact('data', 'tgl1', 'tgl2', 'jenis_pelayanan', 'kelas', 'status', 'keyword'));
    }
}

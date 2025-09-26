<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlaimKompilasiController extends Controller
{
    public function index()
    {
        $klaim = DB::table('reg_periksa')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('bridging_sep', 'reg_periksa.no_rawat', '=', 'bridging_sep.no_rawat')
            ->leftJoin('maping_dokter_dpjpvclaim', 'bridging_sep.kddpjp', '=', 'maping_dokter_dpjpvclaim.kd_dokter_bpjs')
            ->leftJoin('dokter', 'maping_dokter_dpjpvclaim.kd_dokter', '=', 'dokter.kd_dokter')
            ->leftJoin('diagnosa_pasien', function($join) {
                $join->on('reg_periksa.no_rawat', '=', 'diagnosa_pasien.no_rawat')
                     ->where('diagnosa_pasien.prioritas', '=', '1');
            })
            ->leftJoin('kamar_inap', function($join) {
                $join->on('reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
                     ->where('kamar_inap.stts_pulang', '!=', 'Pindah Kamar');
            })
            ->leftJoin('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->leftJoin('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->select(
                'reg_periksa.no_rawat',
                'bridging_sep.no_sep',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'reg_periksa.status_lanjut',
                'reg_periksa.tgl_registrasi',
                DB::raw('DATE(bridging_sep.tglpulang) as tglpulang'),
                'kamar_inap.stts_pulang',
                DB::raw("CASE WHEN reg_periksa.status_lanjut = 'Ranap' THEN CONCAT(kamar_inap.kd_kamar, ' ', bangsal.nm_bangsal) WHEN reg_periksa.status_lanjut = 'Ralan' THEN poliklinik.nm_poli END as ruangan"),
                'dokter.nm_dokter',
                'diagnosa_pasien.kd_penyakit'
            )
            ->where('reg_periksa.status_bayar', 'Sudah Bayar')
            ->whereRaw('LENGTH(bridging_sep.no_sep) = 19')
            ->orderBy('reg_periksa.no_rawat', 'desc')
            ->paginate(10);
        // Tambahkan status kirim dummy
        foreach ($klaim as $k) {
            $k->inacbg_terkirim = rand(0,1) ? true : false;
        }
        return view('klaim-kompilasi', compact('klaim'));
    }
}

<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RawatJalanController extends Controller
{
    public function index(Request $request)
    {
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $pasien = DB::table('reg_periksa')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->where('reg_periksa.status_lanjut', 'Ralan')
            ->where('penjab.png_jawab', 'BPJS')
            ->whereMonth('reg_periksa.tgl_registrasi', $bulanIni)
            ->whereYear('reg_periksa.tgl_registrasi', $tahunIni)
            ->select(
                'reg_periksa.no_reg',
                'reg_periksa.no_rawat',
                'reg_periksa.tgl_registrasi',
                'reg_periksa.jam_reg',
                'reg_periksa.kd_dokter',
                'dokter.nm_dokter',
                'reg_periksa.no_rkm_medis',
                'pasien.nm_pasien',
                'poliklinik.nm_poli',
                'reg_periksa.p_jawab',
                'reg_periksa.almt_pj',
                'reg_periksa.hubunganpj',
                'reg_periksa.biaya_reg',
                'reg_periksa.stts',
                'penjab.png_jawab',
                DB::raw("concat(reg_periksa.umurdaftar,' ',reg_periksa.sttsumur) as umur"),
                'reg_periksa.status_bayar',
                'reg_periksa.status_poli',
                'reg_periksa.kd_pj',
                'reg_periksa.kd_poli',
                DB::raw('(SELECT SUM(totalbiaya) FROM billing WHERE billing.no_rawat = reg_periksa.no_rawat) as biaya_rs')
            )
            ->orderBy('reg_periksa.tgl_registrasi','desc')
            ->paginate(10);
        return view('rawat-jalan', compact('pasien'));
    }
    public function detailStatusBerkas(Request $request)
    {
        $no_rawat = $request->input('no_rawat');
        $detail = [
            'SOAP Ralan' => DB::table('pemeriksaan_ralan')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'SOAP Ranap' => DB::table('pemeriksaan_ranap')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Resume Ralan' => DB::table('resume_pasien')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Resume Ranap' => DB::table('resume_pasien_ranap')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Triase IGD' => DB::table('data_triase_igd')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Askep IGD' => DB::table('penilaian_awal_keperawatan_igd')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis IGD' => DB::table('penilaian_medis_igd')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'ICD-10' => DB::table('diagnosa_pasien')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'ICD-9' => DB::table('prosedur_pasien')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Ralan' => DB::table('penilaian_awal_keperawatan_ralan')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Penyakit Dalam' => DB::table('penilaian_medis_ralan_penyakit_dalam')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Mata' => DB::table('penilaian_medis_ralan_mata')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Gigi' => DB::table('penilaian_awal_keperawatan_gigi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Kebidanan' => DB::table('penilaian_awal_keperawatan_kebidanan')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Kandungan' => DB::table('penilaian_medis_ralan_kandungan')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Ralan Bayi' => DB::table('penilaian_awal_keperawatan_ralan_bayi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Anak' => DB::table('penilaian_medis_ralan_anak')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Ralan Psikiatri' => DB::table('penilaian_awal_keperawatan_ralan_psikiatri')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Fisioterapi' => DB::table('penilaian_fisioterapi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Rehab Medik' => DB::table('penilaian_medis_ralan_rehab_medik')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Psikiatrik' => DB::table('penilaian_medis_ralan_psikiatrik')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Bedah' => DB::table('penilaian_medis_ralan_bedah')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Neurologi' => DB::table('penilaian_medis_ralan_neurologi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Paru' => DB::table('penilaian_medis_ralan_paru')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian MCU' => DB::table('penilaian_mcu')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Kulit & Kelamin' => DB::table('penilaian_medis_ralan_kulitdankelamin')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Orthopedi' => DB::table('penilaian_medis_ralan_orthopedi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Hemodialisa' => DB::table('penilaian_medis_hemodialisa')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Bedah Mulut' => DB::table('penilaian_medis_ralan_bedah_mulut')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Awal Keperawatan Ralan Geriatri' => DB::table('penilaian_awal_keperawatan_ralan_geriatri')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan Geriatri' => DB::table('penilaian_medis_ralan_geriatri')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Penilaian Medis Ralan THT' => DB::table('penilaian_medis_ralan_tht')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'SEP' => DB::table('bridging_sep')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Rekonsiliasi Obat' => DB::table('rekonsiliasi_obat')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Konseling Farmasi' => DB::table('konseling_farmasi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Hasil Lab' => DB::table('periksa_lab')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Gambar Radiologi' => DB::table('gambar_radiologi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
            'Hasil Radiologi' => DB::table('hasil_radiologi')->where('no_rawat', $no_rawat)->exists() ? 'Ada' : 'Tidak Ada',
        ];
        return response()->json($detail);
    }
}

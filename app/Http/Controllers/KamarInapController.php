<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KamarInap;
use App\Models\PiutangPasien;
use Illuminate\Support\Facades\DB;

class KamarInapController extends Controller
{
    public function index()
    {
        // Ambil pasien yang masih dirawat inap (tgl_keluar null atau '0000-00-00')
        $pasien = DB::table('reg_periksa')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->select('reg_periksa.no_rawat', 'reg_periksa.tgl_registrasi', 'dokter.nm_dokter', 'reg_periksa.no_rkm_medis', 'pasien.nm_pasien', 'poliklinik.nm_poli', 'reg_periksa.status_lanjut')
            ->orderBy('reg_periksa.tgl_registrasi', 'desc')
            ->paginate(20);
        // Ambil data piutang/billing
        $piutang = PiutangPasien::all();
        return view('dashboard', compact('pasien', 'piutang'));
    }

    public function pasienRawatInap()
    {
        // Ambil pasien rawat inap aktif dari kamar_inap (hanya yang stts_pulang '-')
        $rawat_inap = DB::table('kamar_inap')
            ->where('stts_pulang', '-')
            ->pluck('no_rawat');

        // Ambil data pasien rawat inap aktif
        $pasien = DB::table('reg_periksa')
            ->join('dokter', 'reg_periksa.kd_dokter', '=', 'dokter.kd_dokter')
            ->join('pasien', 'reg_periksa.no_rkm_medis', '=', 'pasien.no_rkm_medis')
            ->join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->join('penjab', 'reg_periksa.kd_pj', '=', 'penjab.kd_pj')
            ->join('kamar_inap', 'reg_periksa.no_rawat', '=', 'kamar_inap.no_rawat')
            ->join('kamar', 'kamar_inap.kd_kamar', '=', 'kamar.kd_kamar')
            ->join('bangsal', 'kamar.kd_bangsal', '=', 'bangsal.kd_bangsal')
            ->select(
                'reg_periksa.no_rawat',
                'pasien.nm_pasien',
                'kamar_inap.kd_kamar',
                'bangsal.nm_bangsal as nama_kamar',
                'kamar_inap.diagnosa_awal',
                'kamar_inap.tgl_masuk',
                'kamar_inap.lama',
                'kamar_inap.ttl_biaya'
            )
            ->whereIn('reg_periksa.no_rawat', $rawat_inap)
            ->orderBy('reg_periksa.tgl_registrasi', 'desc')
            ->paginate(10);

        $data = [];
        foreach ($pasien as $p) {
            $no_rawat = $p->no_rawat;
            $status_berkas = [];
            // Cek status dokumen sesuai sourcode Java
            $soapiranap = DB::table('pemeriksaan_ranap')->where('no_rawat', $no_rawat)->exists();
            $resumeranap = DB::table('resume_pasien_ranap')->where('no_rawat', $no_rawat)->exists();
            $triaseigd = DB::table('data_triase_igd')->where('no_rawat', $no_rawat)->exists();
            $askepigd = DB::table('penilaian_awal_keperawatan_igd')->where('no_rawat', $no_rawat)->exists();
            $penilaian_medis_igd = DB::table('penilaian_medis_igd')->where('no_rawat', $no_rawat)->exists();
            $icd10 = DB::table('diagnosa_pasien')->where('no_rawat', $no_rawat)->exists();
            $icd9 = DB::table('prosedur_pasien')->where('no_rawat', $no_rawat)->exists();
            $sep = DB::table('bridging_sep')->where('no_rawat', $no_rawat)->exists();
            $dokumen = [
                $soapiranap, $resumeranap, $triaseigd, $askepigd, $penilaian_medis_igd, $icd10, $icd9, $sep
            ];
            $status_berkas = (count(array_filter($dokumen)) == count($dokumen)) ? 'Lengkap' : 'Belum Lengkap';
            $biaya_piutang = DB::table('piutang_pasien')->where('no_rawat', $no_rawat)->value('totalpiutang');
            // Ambil diagnosa dan kelas perawatan
            $diagnosa = $p->diagnosa_awal;
            $kelas = $p->nama_kamar;
            // Query tarif INA-CBGs
            $tarif_inacbg = DB::table('tarif_inacbg')
                ->where('diagnosa', $p->diagnosa_awal)
                ->where('kelas', $p->nama_kamar)
                ->value('tarif');
            // Hitung selisih
            $selisih = $p->ttl_biaya - ($tarif_inacbg ?? 0);
            // Pastikan field lama dan ttl_biaya diisi dengan benar
            $lama_inap = $p->lama ?? 0;
            $total_biaya_rs = $p->ttl_biaya ?? 0;
            // Hitung total biaya RS dari semua komponen billing, termasuk ranap gabung
            $total_biaya_rs = 0;
            $no_rawat_gabung = DB::table('ranap_gabung')->where('no_rawat', $no_rawat)->value('no_rawat2');
            $rawat_list = [$no_rawat];
            if ($no_rawat_gabung) {
                $rawat_list[] = $no_rawat_gabung;
            }
            foreach ($rawat_list as $rawat) {
                // Registrasi
                $total_biaya_rs += DB::table('reg_periksa')->where('no_rawat', $rawat)->value('biaya_reg') ?? 0;
                // Laborat
                $total_biaya_rs += DB::table('periksa_lab')->where('no_rawat', $rawat)->sum('biaya') ?? 0;
                $total_biaya_rs += DB::table('detail_periksa_lab')->where('no_rawat', $rawat)->sum('biaya_item') ?? 0;
                // Radiologi
                $total_biaya_rs += DB::table('periksa_radiologi')->where('no_rawat', $rawat)->sum('biaya') ?? 0;
                // Operasi
                $total_biaya_rs += DB::table('operasi')->where('no_rawat', $rawat)->sum(DB::raw('biayaoperator1+biayaoperator2+biayaoperator3+biayaasisten_operator1+biayaasisten_operator2+biayaasisten_operator3+biayainstrumen+biayadokter_anak+biayaperawaat_resusitas+biayadokter_anestesi+biayaasisten_anestesi+biayaasisten_anestesi2+biayabidan+biayabidan2+biayabidan3+biayaperawat_luar+biayaalat+biayasewaok+akomodasi+bagian_rs+biaya_omloop+biaya_omloop2+biaya_omloop3+biaya_omloop4+biaya_omloop5+biayasarpras+biaya_dokter_pjanak+biaya_dokter_umum')) ?? 0;
                // Obat
                $total_biaya_rs += DB::table('detail_pemberian_obat')->where('no_rawat', $rawat)->sum('total') ?? 0;
                $total_biaya_rs += DB::table('tagihan_obat_langsung')->where('no_rawat', $rawat)->sum('besar_tagihan') ?? 0;
                $total_biaya_rs += DB::table('beri_obat_operasi')->where('no_rawat', $rawat)->sum(DB::raw('hargasatuan*jumlah')) ?? 0;
                // Kamar
                $total_biaya_rs += DB::table('kamar_inap')->where('no_rawat', $rawat)->sum('ttl_biaya') ?? 0;
                // Tambahan
                $total_biaya_rs += DB::table('tambahan_biaya')->where('no_rawat', $rawat)->sum('besar_biaya') ?? 0;
                // Potongan
                $total_biaya_rs -= DB::table('pengurangan_biaya')->where('no_rawat', $rawat)->sum('besar_pengurangan') ?? 0;
                // Harian
                $total_biaya_rs += DB::table('biaya_harian')->where('kd_kamar', $p->kd_kamar)->sum(DB::raw('jml*besar_biaya*'.$p->lama)) ?? 0;
                // Retur Obat
                $total_biaya_rs -= DB::table('detreturjual')->where('no_retur_jual', 'like', '%'.$rawat.'%')->sum('subtotal') ?? 0;
                // Resep Pulang
                $total_biaya_rs += DB::table('resep_pulang')->where('no_rawat', $rawat)->sum('total') ?? 0;
                // Deposit
                $total_biaya_rs -= DB::table('deposit')->where('no_rawat', $rawat)->sum('besar_deposit') ?? 0;
            }
            $data[] = [
                'no_rawat' => $p->no_rawat,
                'nm_pasien' => $p->nm_pasien,
                'kd_kamar' => $p->kd_kamar,
                'nama_kamar' => $p->nama_kamar,
                'diagnosa_awal' => $p->diagnosa_awal,
                'tgl_masuk' => $p->tgl_masuk,
                'lama' => $lama_inap,
                'ttl_biaya' => $total_biaya_rs,
                'biaya_piutang' => $biaya_piutang,
                'tarif_inacbg' => $tarif_inacbg ?? 0,
                'selisih' => $total_biaya_rs - ($tarif_inacbg ?? 0),
                'status_berkas' => $status_berkas
            ];
        }
        // Kirim data pagination ke view
        return view('pasien-rawat-inap', ['pasien' => $data, 'pagination' => $pasien]);
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

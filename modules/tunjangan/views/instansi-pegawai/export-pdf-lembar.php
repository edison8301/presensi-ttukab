<?php

/* @var $searchModel \app\models\InstansiPegawaiSearch */
/* @var $allInstansiPegawai \app\models\InstansiPegawai[] */
/* @var $this \yii\web\View */
/* @var $modelExportPdf \app\modules\absensi\models\ExportPdf */

use app\components\Helper;
use app\components\Session;
use app\components\TunjanganBulan;
use app\models\Jabatan;
use app\models\Pegawai;
use app\models\TunjanganInstansiJenisJabatanKelas;
use app\models\User;

?>

<div style="text-align: center;">DAFTAR NOMINATIF PEMBAYARAN BERDASARKAN KRITERIA TAMBAHAN PENGHASILAN PEGAWAI ASN</div>
<div style="text-align: center;">PEMERINTAH KABUPATEN TIMOR TENGAH UTARA</div>
<div style="text-align: center;">TAHUN ANGGARAN <?= User::getTahun(); ?></div>

<div>&nbsp;</div>

<table>
    <tr>
        <td style="width: 200px">PD / UNIT KERJA</td>
        <td style="width: 10px">:</td>
        <td><?= @$searchModel->instansi->nama; ?></td>
    </tr>
    <tr>
        <td>BULAN</td>
        <td>:</td>
        <td style="text-transform:uppercase"><?= $searchModel->getBulanLengkapTahun(); ?></td>
    </tr>
</table>

<div>&nbsp;</div>

<table cellpadding="6" border="1" style="background: #ffffff; margin-bottom:20px">
    <thead>
    <tr>
        <th width="10px">NO</th>
        <th>NAMA/NIP</th>
        <th>GOL</th>
        <th>JABATAN</th>
        <th>KELAS JABATAN</th>
        <th>BESARAN TPP</th>
        <th>BEBAN KERJA</th>
        <th>PRESTASI KERJA</th>
        <th>KONDISI KERJA</th>
        <th>TEMPAT BERTUGAS</th>
        <th>KELANGKAAN PROFESI</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
    </tr>
    </thead>
    <?php
        $jumlah_nilai_tpp = 0;
        $jumlah_beban_kerja = 0;
        $jumlah_prestasi_kerja = 0;
        $jumlah_kondisi_kerja = 0;
        $jumlah_tempat_bertugas = 0;
        $jumlah_kelangkaan_profesi = 0;
    ?>
    <?php $i=1; foreach($allInstansiPegawai as $instansiPegawai) { ?>

        <?php $pegawaiTunjangan = $instansiPegawai->pegawai->findOrCreatePegawaiTunjangan(['bulan'=>$searchModel->bulan]); ?>
        <?php $pegawai = \app\modules\tukin\models\Pegawai::findOne($instansiPegawai->id_pegawai); ?>
        <?php
            $penundaan = null;
            if ($searchModel->jenis == 'penundaan-tpp') {
                $penundaan = false;
            }

            $pegawai->getRupiahTPPSebelumPajak($searchModel->bulan,[
                'tukin'=>true,
                'penundaan' => $penundaan,
            ]);
            $pegawai->getRupiahTunjanganPlt($searchModel->bulan);
            $jumlahKotorTpp = $pegawai->getJumlahKotorTPP($searchModel->bulan);

            /**
             * Pada tahun 2022 dari bulan 1-6 untuk tubel dibayar 70%
             * Pada bulan 7 kedepan persen(%) dibayar diambil dari menu "Pegawai Tugas Belajar"
             */
            if (Session::getTahun() == 2022 AND $searchModel->bulan <= 6) {
                if(substr(@$instansiPegawai->nama_jabatan,0,13) == 'Tugas Belajar') { // KHUSUS TUBEL 70%
                    $jumlahKotorTpp = $jumlahKotorTpp * 0.7;
                }
            }

            $besaran_tpp = TunjanganInstansiJenisJabatanKelas::findOneByParams([
                'bulan' => $searchModel->bulan,
                'tahun' => $searchModel->tahun,
                'instansi' => $instansiPegawai->instansi,
                'jabatan' => $instansiPegawai->jabatan,
                'pegawai' => $instansiPegawai->pegawai
            ]);
        ?>
        <tr>
            <td style="text-align:center"><?= $i++; ?></td>
            <td>
                <?= $pegawai->nama; ?><br>
                <?= 'NIP.'.$pegawai->nip; ?>
            </td>
            <td>
                <?= @$pegawai->getNamaPegawaiGolonganBerlaku(['bulan'=>$searchModel->bulan]); ?>
            </td>
            <td>
                <?= @$instansiPegawai->nama_jabatan; ?>
            </td>
            <td style="text-align: center;">
                <?= @$instansiPegawai->jabatan->kelas_jabatan; ?>
            </td>
            <?php if ($besaran_tpp !== null) { ?>
                <td style="text-align: right">
                    <?= Helper::rp($jumlahKotorTpp,0); ?>
                    <?php $jumlah_nilai_tpp = $jumlah_nilai_tpp + $jumlahKotorTpp; ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($besaran_tpp->getBesaran('beban_kerja_persen', $jumlahKotorTpp),0); ?>
                    <?php $jumlah_beban_kerja = $jumlah_beban_kerja + $besaran_tpp->getBesaran('beban_kerja_persen', $jumlahKotorTpp); ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($besaran_tpp->getBesaran('prestasi_kerja_persen', $jumlahKotorTpp),0); ?>
                    <?php $jumlah_prestasi_kerja = $jumlah_prestasi_kerja + $besaran_tpp->getBesaran('prestasi_kerja_persen', $jumlahKotorTpp); ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($besaran_tpp->getBesaran('kondisi_kerja_persen', $jumlahKotorTpp),0); ?>
                    <?php $jumlah_kondisi_kerja = $jumlah_kondisi_kerja + $besaran_tpp->getBesaran('kondisi_kerja_persen', $jumlahKotorTpp); ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($besaran_tpp->getBesaran('tempat_bertugas_persen', $jumlahKotorTpp),0); ?>
                    <?php $jumlah_tempat_bertugas = $jumlah_tempat_bertugas + $besaran_tpp->getBesaran('tempat_bertugas_persen', $jumlahKotorTpp); ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($besaran_tpp->getBesaran('kelangkaan_profesi_persen', $jumlahKotorTpp),0); ?>
                    <?php $jumlah_kelangkaan_profesi = $jumlah_kelangkaan_profesi + $besaran_tpp->getBesaran('kelangkaan_profesi_persen', $jumlahKotorTpp); ?>
                </td>
            <?php } else { ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            <?php } ?>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="5"><b>Total</b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_nilai_tpp) ?></b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_beban_kerja) ?></b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_prestasi_kerja) ?></b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_kondisi_kerja) ?></b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_tempat_bertugas) ?></b></td>
        <td style="text-align: right;"><b><?= Helper::rp($jumlah_kelangkaan_profesi) ?></b></td>
    </tr>
</table>

<?php
/** @var Jabatan $jabatanKepala */
$jabatanKepala = $modelExportPdf->instansi->getManyJabatanKepala()->one();
$nama = null;
$nip = null;
if ($jabatanKepala) {

    $tanggal = date('Y-m-d');
    $datetime = \DateTime::createFromFormat('Y-n-d', $searchModel->tahun . '-' . $searchModel->bulan . '-01');
    if ($datetime !== false) {
        $tanggal = $datetime->format('Y-m-15');
    }

    /** @var Pegawai $instansiPegawaiKepala */
    $instansiPegawaiKepala = $jabatanKepala->getManyInstansiPegawai()
        ->orderBy(['tanggal_mulai'=>SORT_DESC])
        ->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal', [
            ':tanggal' => $tanggal
        ])
        ->one();

    if ($instansiPegawaiKepala) {
        $nama = @$instansiPegawaiKepala->pegawai->nama;
        $nip = @$instansiPegawaiKepala->pegawai->nip;
    }
}
?>

<?php if($tandatangan == false) { ?>
    <table class="table" style="page-break-inside: avoid">
        <tr>
            <td style="width: 78%"></td>
            <td>Pangkalpinang,</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">Menyetujui</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center"><?= $jabatanKepala->nama; ?></td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center"><?= $nama ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">NIP. <?= $nip ?></td>
        </tr>
    </table>
<?php } ?>

<?php if($tandatangan == true) { ?>
    <?= $this->render('_tandatangan-elektronik', [
        'nama' => $nama,
        'jabatan' => @$jabatanKepala->nama,
        'nip' => $nip,
    ]); ?>
<?php } ?>

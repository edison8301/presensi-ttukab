<?php

/* @var $searchModel \app\models\InstansiPegawaiSearch */
/* @var $query \app\models\InstansiPegawaiQuery */
/* @var $this \yii\web\View */
/* @var $modelExportPdf \app\modules\absensi\models\ExportPdf */

use app\components\Helper;
use app\models\Jabatan;
use app\models\Pegawai;
use app\models\User;

?>

<div style="text-align: center;">DAFTAR NOMINATIF PEMBAYARAN TAMBAHAN PENGHASILAN BERDASARKAN BEBAN KERJA</div>
<div style="text-align: center;">TAHUN ANGGARAN <?= User::getTahun(); ?></div>

<div>&nbsp;</div>

<table>
    <tr>
        <td>PD / UNIT KERJA</td>
        <td>:</td>
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
        <th rowspan="2" width="10px">NO</th>
        <th rowspan="2">NAMA/NIP</th>
        <th rowspan="2">GOL</th>
        <th rowspan="2">JABATAN</th>
        <th colspan="4">PERHITUNGAN TPP</th>
        <th colspan="3">PPh. PASAL 21</th>
        <th rowspan="2">JUMLAH BERSIH</th>
    </tr>
    <tr>
        <th width="10px">JUMLAH TPP SEBELUM PAJAK</th>
        <th>TUNJANGAN PLT. (20% DARI BESARAN TPP)</th>
        <th width="10px">VOL/BLN</th>
        <th width="10px">JUMLAH KOTOR</th>
        <th width="75px">GOL.IV</th>
        <th width="10px">GOL.III</th>
        <th width="10px">JUMLAH PPh</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7=5X6</th>
        <th>8</th>
        <th>9</th>
        <th>10=8+9</th>
        <th>11</th>
        <th>12=(7-10)+11</th>
    </tr>
    </thead>
    <?php $i=1; foreach($query->all() as $instansiPegawai) { ?>

        <?php $pegawaiTunjangan = $instansiPegawai->pegawai->findOrCreatePegawaiTunjangan(['bulan'=>$searchModel->bulan]); ?>
        <?php $pegawai = \app\modules\tukin\models\Pegawai::findOne($instansiPegawai->id_pegawai); ?>
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
                <?= @$instansiPegawai->jabatan->nama; ?>
            </td>
            <td style="text-align: right">
                <?= Helper::rp($pegawai->getRupiahTPPSebelumPajak($searchModel->bulan,[
                    'tukin'=>true
                ]),0); ?>
            </td>
            <td style="text-align: right"><?= Helper::rp($pegawai->getRupiahTunjanganPlt($searchModel->bulan),0); ?></td>
            <td style="text-align: center"><?= $pegawai->getVolumePerBulan(); ?></td>
            <td style="text-align: right"><?= Helper::rp($pegawai->getJumlahKotorTPP($searchModel->bulan)); ?></td>
            <td style="text-align: center"><?= number_format($pegawai->getPotonganPajakByGolonganIV(['bulan'=>$searchModel->bulan]),2); ?> %</td>
            <td style="text-align: center"><?= number_format($pegawai->getPotonganPajakByGolonganIII(['bulan'=>$searchModel->bulan]),2); ?> %</td>
            <td style="text-align: center"><?= number_format($pegawai->getPersenPotonganPajak(['bulan'=>$searchModel->bulan]),2); ?> %</td>
            <td style="text-align: right"><?= Helper::rp($pegawai->getRupiahTPPBersih($searchModel->bulan)); ?></td>
        </tr>
    <?php } ?>
</table>

<?php
/** @var Jabatan $jabatanKepala */
$jabatanKepala = $modelExportPdf->instansi->getManyJabatanKepala()->one();
$nama = null;
$nip = null;
if ($jabatanKepala) {
    /** @var Pegawai $instansiPegawaiKepala */
    $instansiPegawaiKepala = $jabatanKepala->getManyInstansiPegawai()
        ->orderBy(['tanggal_mulai'=>SORT_DESC])
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

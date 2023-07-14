<?php

use app\models\Pengaturan;
use kartik\editable\Editable;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PengaturanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allPengaturan \app\models\Pengaturan[] */

$this->title = 'Daftar Pengaturan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-index box box-primary">

    <div class="box-header">
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="text-align: center; width: 30px">No</th>
                <th style="text-align: center;">Pengaturan</th>
                <th style="text-align: center; width: 300px;">Nilai</th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::BATAS_PENGAJUAN); ?>
            <td style="text-align: center;">1</td>
            <td>Batas Pengajuan</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditableBatasPengajuan() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::TANGGAL_BATAS_PENGAJUAN_BERLAKU); ?>
            <td style="text-align: center;">2</td>
            <td>Tanggal Batas Pengajuan Berlaku</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditableTanggalBatasPengajuanBerlaku() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::JUMLAH_BATAS_PENGAJUAN_HARI_KERJA); ?>
            <td style="text-align: center;">3</td>
            <td>Jumlah Batas Pengajuan Hari Kerja</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditable() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::METODE_PERHITUNGAN_BESAR_TPP_AWAL); ?>
            <td style="text-align: center;">4</td>
            <td>Metode Perhitungan Besar TPP Awal</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditableMetodePerhitunganBesaranTppAwal() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::TAMPILKAN_NILAI_RUPIAH_TUNJANGAN_PADA_USER_PEGAWAI); ?>
            <td style="text-align: center;">5</td>
            <td>Tampilkan Nilai Rupiah Tunjangan Pada User Pegawai</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditableTampilkanNilaiRupiahTunjanganPadaUserPegawai() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::CEK_IMEI); ?>
            <td style="text-align: center;">6</td>
            <td>Cek IMEI</td>
            <td style="text-align: center;">
                <?= $pengaturan->getEditableCekImei() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_POTONGAN_CKHP); ?>
            <td style="text-align: center;">7</td>
            <td>Persen (%) Potongan CKHP</td>
            <td style="text-align: center;">
                <?= $pengaturan->getNilaiBerlaku() ?>%
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_DIBAYAR_CUTI_ALASAN_PENTING); ?>
            <td style="text-align: center;">8</td>
            <td>Persen (%) Diberikan TPP Cuti Alasan Penting</td>
            <td style="text-align: center;">
                <?= $pengaturan->getNilaiBerlaku() === null ? 'Tidak Aktif' : $pengaturan->getNilaiBerlaku() . '%' ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_DIBAYAR_CUTI_SAKIT); ?>
            <td style="text-align: center;">9</td>
            <td>Persen (%) Diberikan TPP Cuti Sakit</td>
            <td style="text-align: center;">
                <?= $pengaturan->getNilaiBerlaku() === null ? 'Tidak Aktif' : $pengaturan->getNilaiBerlaku() . '%' ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::PERSEN_POTONGAN_INDEKS_PROFESIONALITAS); ?>
            <td style="text-align: center;">10</td>
            <td>Persen (%) Potongan Tidak Memenuhi Ketercapaian IP ASN</td>
            <td style="text-align: center;">
                <?= $pengaturan->getNilaiBerlaku() ?>%
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <tr>
            <?php $pengaturan = Pengaturan::findOne(Pengaturan::MINIMAL_SKOR_IP_ASN); ?>
            <td style="text-align: center;">11</td>
            <td>Minimal Skor IP ASN</td>
            <td style="text-align: center;">
                <?= $pengaturan->getNilaiBerlaku() === null ? '<i>(Belum diset)</i>' : $pengaturan->getNilaiBerlaku() ?>
            </td>
            <td style="text-align: center;">
                <?= $pengaturan->getLinkPengaturanBerlakuIcon() ?>
            </td>
        </tr>
        <?php /* $i = 1; ?>
        <?php foreach ($allPengaturan as $pengaturan): ?>
            <tr>
                <td style="text-align: center;"><?= $i++; ?></td>
                <td style="text-align: left;"><?= $pengaturan->getNama(); ?></td>
                <td style="text-align: center;">
                    <?= $pengaturan->getEditableNilai(); ?>
                </td>
                <td style="text-align: center">
                    <?= Html::a('<i class="fa fa-calendar"></i>',[
                        '/pengaturan-berlaku/index',
                        'id_pengaturan' => $pengaturan->id
                    ]); ?>
                </td>
            </tr>
        <?php endforeach */ ?>
    </table>
    </div>
</div>

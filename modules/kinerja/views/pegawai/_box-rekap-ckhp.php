<?php

use app\components\Helper;
use app\components\kinerja\KinerjaBulan;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $kinerjaBulan KinerjaBulan */

?>

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Rekap Kinerja Harian</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th rowspan="2" style="text-align: center; width:100px">Tanggal</th>
                <th rowspan="2" style="text-align: center; width:150px">Hari</th>
                <th colspan="4" style="text-align: center">Jumlah Kinerja Harian</th>
                <th rowspan="2" style="text-align: center">% Potong</th>
                <th rowspan="2">&nbsp</th>
            </tr>
            <tr>
                <th style="text-align: center">Konsep</th>
                <th style="text-align: center">Periksa</th>
                <th style="text-align: center">Tolak</th>
                <th style="text-align: center">Setuju</th>
            </tr>
            </thead>
            <?php foreach ($kinerjaBulan->getArrayKinerjaHari() as $kinerjaHari) { ?>
                <tr>
                    <td style="text-align: center;">
                        <?= Helper::getTanggalSingkat($kinerjaHari->tanggal) ?>
                    </td>
                    <td style="text-align: center;">
                        <?= Helper::getHari($kinerjaHari->tanggal) ?>
                    </td>
                    <td style="text-align: center;">
                        <?php $label = Helper::rp($kinerjaHari->getJumlahKegiatanHarianKonsep(), 0) ?>
                        <?= Html::a($label, [
                            '/kinerja/kegiatan-harian/index-v4',
                            'KegiatanHarianSearch[tanggal]' => $kinerjaHari->tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP
                        ],[
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kinerja Konsep'
                        ]); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php $label = Helper::rp($kinerjaHari->getJumlahKegiatanHarianPeriksa(), 0) ?>
                        <?= Html::a($label, [
                            '/kinerja/kegiatan-harian/index-v4',
                            'KegiatanHarianSearch[tanggal]' => $kinerjaHari->tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA
                        ],[
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kinerja Konsep'
                        ]); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php $label = Helper::rp($kinerjaHari->getJumlahKegiatanHarianTolak(), 0) ?>
                        <?= Html::a($label, [
                            '/kinerja/kegiatan-harian/index-v4',
                            'KegiatanHarianSearch[tanggal]' => $kinerjaHari->tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK
                        ],[
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kinerja Konsep'
                        ]); ?>
                    </td>
                    <td style="text-align: center;">
                        <?php $label = Helper::rp($kinerjaHari->getJumlahKegiatanHarianSetuju(), 0) ?>
                        <?= Html::a($label,[
                            '/kinerja/kegiatan-harian/index-v4',
                            'KegiatanHarianSearch[tanggal]' => $kinerjaHari->tanggal,
                            'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU
                        ],[
                            'data-toggle'=>'tooltip',
                            'title'=>'Jumlah Kinerja Konsep'
                        ]); ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kinerjaHari->getStringPersenPotongan() ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $kinerjaHari->getLinkCreateKegiatanHarianIcon([
                            'id_kegiatan_harian_jenis' => KegiatanHarianJenis::UTAMA,
                        ]) ?>
                        <?= $kinerjaHari->getLinkCreateKegiatanHarianIcon([
                            'id_kegiatan_harian_jenis' => KegiatanHarianJenis::TAMBAHAN,
                        ]) ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th style="text-align: center;" colspan="6">Total</th>
                <th style="text-align: center;">
                    <?= $kinerjaBulan->getTotalPersenPotongan() ?>
                </th>
                <th></th>
            </tr>
        </table>
    </div>
</div>

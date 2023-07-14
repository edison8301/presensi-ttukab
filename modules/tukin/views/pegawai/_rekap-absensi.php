<?php

use yii\widgets\DetailView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $rekap \app\modules\tukin\models\PegawaiRekapTunjangan */
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rekap Absensi
        </h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $rekap->pegawaiRekapAbsensi !== null ? $rekap->pegawaiRekapAbsensi : new \app\modules\tukin\models\PegawaiRekapAbsensi(),
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'jumlah_hari_kerja',
                'jumlah_hadir',
                'jumlah_tidak_hadir',
                'persen_potongan_fingerprint',
                [
                    'attribute' => 'waktu_diperbarui',
                    'value' => function ($data, $widget) {
                        return Helper::getWaktuWIB($data->waktu_diperbarui);
                    }
                ],
            ],
        ]) ?>
    </div>
</div>

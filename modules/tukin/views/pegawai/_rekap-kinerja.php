<?php

use yii\widgets\DetailView;
use app\components\Helper;

/* @var $rekap \app\modules\tukin\models\PegawaiRekapTunjangan */
/* @var $this \yii\web\View */
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rekap Kinerja
        </h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $rekap->pegawaiRekapKinerja !== null ? $rekap->pegawaiRekapKinerja : new \app\modules\tukin\models\PegawaiRekapKinerja(),
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                'potongan_skp',
                'potongan_ckhp',
                'potongan_total',
                [
                    'attribute' => 'waktu_update',
                    'value' => function ($data, $widget) {
                        return Helper::getWaktuWIB($data->waktu_update);
                    }
                ],
            ],
        ]) ?>
    </div>
</div>

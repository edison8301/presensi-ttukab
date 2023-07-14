<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KegiatanBulananBreakdown */

$this->title = "Kegiatan Bulanan Breakdown";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulanan Breakdowns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulanan-breakdown-view box box-primary">


    <div class="box-header">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id_kegiatan_tahunan_detil',
                'kegiatan:ntext',
                'kuantitas',
                'id_satuan_kuantitas',
                'penilaian_kualitas',
            ],
        ]) ?>
    </div>

</div>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Kegiatan Harian</h3>
    </div>
    <div class="box-body">
        <table class="table">
        <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kegiatan Harian</th>
            <th>Kuantitas</th>
        </tr>
        </thead>
        <?php $i=1; foreach($model->findAllKegiatanHarian() as $data) { ?>
        <tr>
            <td><?= $i; ?></td>
            <td><?= $data->tanggal; ?></td>
            <td><?= $data->nama_kegiatan; ?></td>
            <td><?= $data->kuantitas; ?></td>
        <?php $i++; } ?>
        </table>
    </div>
</div>

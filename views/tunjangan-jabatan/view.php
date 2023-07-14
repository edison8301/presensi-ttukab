<?php

use app\components\Helper;
use app\models\TunjanganKomponen;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatan */

$this->title = "Detail Tunjangan Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-jabatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Tunjangan Jabatan</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => @$model->jabatan->nama,
            ],
            [
                'attribute' => 'jumlah_tunjangan',
                'format' => 'raw',
                'value' => Helper::rp($model->jumlah_tunjangan,0),
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => $model->tanggal_mulai,
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => $model->tanggal_selesai,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Jabatan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Jabatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="box box-primary">

    <div class="box-header">
        <h3 class="box-title">Komponen Kelas</h3>
    </div>

    <div class="box-body">
        <table class="table">
            <tr>
                <th style="text-align: center; width: 50px">No</th>
                <th>Komponen</th>
                <th style="text-align: center">Jumlah Tunjangan</th>
                <th style="text-align: center">Status</th>
            </tr>
            <?php $i=1; foreach(TunjanganKomponen::find()->all() as $data) { ?>
                <?php $kelasKomponen = $model->findOrCreateTunjanganJabatanKomponen(['id_tunjangan_komponen'=>$data->id]); ?>
                <tr>
                    <td style="text-align: center"><?= $i; ?></td>
                    <td><?= $data->nama; ?></td>
                    <td style="text-align: right"><?= $kelasKomponen->getEditableJumlahTunjangan(); ?></td>
                    <td style="text-align: center"><?= $kelasKomponen->getEditableStatusAktif(); ?></td>
                </tr>
                <?php $i++; } ?>
        </table>
    </div>
</div>

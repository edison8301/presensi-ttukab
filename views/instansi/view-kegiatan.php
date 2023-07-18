<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */

$this->title = "Detail Perangkat Daerah";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Perangkat Daerah</h3>
    </div>

	<div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'label' => 'Nama',
                    'format' => 'raw',
                    'value' => $model->nama
                ],
            ],
        ]) ?>

    </div>
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Rekap Kehadiran</h3>
    </div>
    <div class="box-body">
        
        <?= $this->render('_grid-kegiatan', [
            'instansi' => $model
        ]) ?>

    </div>
</div>

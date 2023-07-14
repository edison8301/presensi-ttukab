<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganFungsional */

$this->title = "Detail Jabatan Tunjangan Fungsional";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Fungsional', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-fungsional-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan Tunjangan Fungsional</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->id,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'id_tingkatan_fungsional',
                'format' => 'raw',
                'value' => $model->id_tingkatan_fungsional,
            ],
            [
                'attribute' => 'besaran_tpp',
                'format' => 'raw',
                'value' => $model->besaran_tpp,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan Tunjangan Fungsional', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan Tunjangan Fungsional', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

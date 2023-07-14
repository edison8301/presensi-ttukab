<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganPelaksana */

$this->title = "Detail Jabatan Tunjangan Pelaksana";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Pelaksana', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-pelaksana-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan Tunjangan Pelaksana</h3>
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
                'attribute' => 'id_jabatan_golongan',
                'format' => 'raw',
                'value' => $model->id_jabatan_golongan,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan Tunjangan Pelaksana', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan Tunjangan Pelaksana', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

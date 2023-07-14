<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganNilai */

$this->title = "Detail Tunjangan Potongan Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongan Nilai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-potongan-nilai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Tunjangan Potongan Nilai</h3>
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
                'attribute' => 'id_tunjangan_potongan',
                'format' => 'raw',
                'value' => $model->id_tunjangan_potongan,
            ],
            [
                'attribute' => 'nilai',
                'format' => 'raw',
                'value' => $model->nilai,
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
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Potongan Nilai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Potongan Nilai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

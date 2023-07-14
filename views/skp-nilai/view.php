<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SkpNilai */

$this->title = "Detail Skp Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Skp Nilai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-nilai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Skp Nilai</h3>
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
                'attribute' => 'id_instansi_pegawai_skp',
                'format' => 'raw',
                'value' => $model->id_instansi_pegawai_skp,
            ],
            [
                'attribute' => 'id_skp_periode',
                'format' => 'raw',
                'value' => $model->id_skp_periode,
            ],
            [
                'attribute' => 'periode',
                'format' => 'raw',
                'value' => $model->periode,
            ],
            [
                'attribute' => 'feedback_hasil_kerja',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'value' => $model->feedback_hasil_kerja
            ],
            [
                'attribute' => 'nilai_hasil_kerja',
                'format' => 'raw',
                'value' => $model->nilai_hasil_kerja,
            ],
            [
                'attribute' => 'feedback_perilaku_kerja',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'value' => $model->feedback_perilaku_kerja
            ],
            [
                'attribute' => 'nilai_perilaku_kerja',
                'format' => 'raw',
                'value' => $model->nilai_perilaku_kerja,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Skp Nilai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Skp Nilai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

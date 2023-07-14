<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\InstansiSerapanAnggaran */

$this->title = "Detail Instansi Serapan Anggaran";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Serapan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-serapan-anggaran-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi Serapan Anggaran</h3>
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
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->bulan,
            ],
            [
                'attribute' => 'target',
                'format' => 'raw',
                'value' => $model->target,
            ],
            [
                'attribute' => 'realisasi',
                'format' => 'raw',
                'value' => $model->realisasi,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Serapan Anggaran', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Serapan Anggaran', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

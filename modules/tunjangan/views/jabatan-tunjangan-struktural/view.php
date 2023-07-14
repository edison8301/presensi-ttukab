<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganStruktural */

$this->title = "Detail Jabatan Tunjangan Struktural";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Struktural', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-struktural-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan Tunjangan Struktural</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'id_eselon',
                'format' => 'raw',
                'value' => @$model->eselon->nama,
            ],
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'value' => @$model->golongan->nama,
            ],            
            [
                'attribute' => 'besaran_tpp',
                'format' => 'raw',
                'value' => Helper::rp($model->besaran_tpp),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Jabatan Tunjangan Struktural', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan Tunjangan Struktural', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

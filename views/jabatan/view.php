<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Jabatan */

$this->title = "Detail Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan</h3>
    </div>

	<div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'attribute'=>'nama',
            	'format'=>'raw',
                'value'=>$model->nama
            ],
            [
                'attribute'=>'status_kepala',
                'label' => 'Kepala UKE',
                'format'=>'raw',
                'value'=>$model->getNamaStatusKepala()
            ],
            [
                'label'=>'Instansi',
                'format'=>'raw',
                'value'=>@$model->instansi->nama
            ],
            [
                'label'=>'Atasan',
                'format'=>'raw',
                'value'=>@$model->jabatanInduk->nama
            ],
            [
                'label'=>'Nilai Jabatan',
                'format'=>'raw',
                'value'=>@$model->nilai_jabatan
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?= $model->getLinkUpdateAtasanKepala(); ?>
    </div>

</div>

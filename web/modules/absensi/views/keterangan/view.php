<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Keterangan */

$this->title = "Detail Keterangan";
$this->params['breadcrumbs'][] = ['label' => 'Keterangan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keterangan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Keterangan</h3>
    </div>
    
	<div class="box-body">        

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'attribute'=>'id',
            	'format'=>'raw',
                'value'=>$model->id        	],
    		[
            	'attribute'=>'nip',
            	'format'=>'raw',
                'value'=>$model->nip        	],
    		[
            	'attribute'=>'tanggal',
            	'format'=>'raw',
                'value'=>$model->tanggal        	],
    		[
            	'attribute'=>'id_keterangan_jenis',
            	'format'=>'raw',
                'value'=>$model->id_keterangan_jenis        	],
    		[
            	'attribute'=>'keterangan',
            	'format'=>'raw',
                'value'=>$model->keterangan        	],
    		[
            	'attribute'=>'status',
            	'format'=>'raw',
                'value'=>$model->status        	],
    		[
            	'attribute'=>'lampiran',
            	'format'=>'raw',
                'value'=>$model->lampiran        	],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Keterangan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Keterangan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

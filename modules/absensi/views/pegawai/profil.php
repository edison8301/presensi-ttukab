<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai</h3>
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
            	'attribute'=>'nip',
            	'format'=>'raw',
                'value'=>$model->nip        	
            ],
    		[
                'attribute'=>'id_jabatan',
                'format'=>'raw',
                'value'=>$model->jabatan ? $model->jabatan->nama : ''
            ],
            [
            	'attribute'=>'id_instansi',
            	'format'=>'raw',
                'value'=>($model->instansi ? $model->instansi->nama : '').' '.Html::a('<i class="fa fa-pencil"</i>',['pegawai/profil-update','mode'=>'id_instansi'],['data-toggle'=>'tooltip','title'=>'Ubah Instansi'])
            ],
            [
                'attribute'=>'id_atasan',
                'format'=>'raw',
                'value'=>($model->atasan ? $model->atasan->nama : '').' '.Html::a('<i class="fa fa-pencil"</i>',['pegawai/profil-update','mode'=>'id_atasan'],['data-toggle'=>'tooltip','title'=>'Ubah Atasan'])
            ],
            [
                'attribute'=>'telepon',
                'format'=>'raw',
                'value'=>$model->telepon
            ],
            [
                'attribute'=>'email',
                'format'=>'raw',
                'value'=>$model->email
            ],
        ],
    ]) ?>

    </div>

</div>

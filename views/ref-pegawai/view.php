<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RefPegawai */

$this->title = "Detail Ref Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Ref Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ref Pegawai</h3>
    </div>
    
	<div class="box-body">        

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'attribute'=>'id',
            	'format'=>'raw',
                'value'=>$model->id        	
            ],
    		[
            	'attribute'=>'kode_pegawai',
            	'format'=>'raw',
                'value'=>$model->kode_pegawai        	
            ],
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
            	'attribute'=>'kode_absensi',
            	'format'=>'raw',
                'value'=>$model->kode_absensi        	
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ref Pegawai', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Ref Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="ref-pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Data Pegawai</h3>
    </div>
    
    <div class="box-body">     
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">No</th>
            <th style="text-align: center">Tahun</th>
            <th style="text-align: center">Nama</th>
            <th style="text-align: center">Instansi</th>
        </tr>
        </thead>
        <?php $i=1; foreach($model->getAllPegawai()->all() as $pegawai) { ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td style="text-align: center"><?= $pegawai->tahun; ?></td>
            <td style="text-align: center"><?= $pegawai->getRelationField("refPegawai","nama"); ?></td>
            <td style="text-align: center"><?= $pegawai->getRelationField("refInstansi","nama"); ?></td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>

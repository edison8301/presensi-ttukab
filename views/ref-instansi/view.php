<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RefInstansi */

$this->title = "Detail Ref Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Ref Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ref Instansi</h3>
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
            	'attribute'=>'kode_instansi',
            	'format'=>'raw',
                'value'=>$model->kode_instansi        	
            ],
    		[
            	'attribute'=>'nama',
            	'format'=>'raw',
                'value'=>$model->nama        	
            ],
    		[
            	'attribute'=>'alamat',
            	'format'=>'raw',
                'value'=>$model->alamat        	
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

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ref Instansi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Ref Instansi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
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
            <th style="text-align: center">Jumlah Pegawai</th>
        </tr>
        </thead>
        <?php $i=1; foreach($model->getAllInstansi()->all() as $instansi) { ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td style="text-align: center"><?= $instansi->tahun; ?></td>
            <td style="text-align: center"><?= $instansi->getRelationField("refInstansi","nama"); ?></td>
            <td style="text-align: center"><?= $instansi->getAllPegawai()->count(); ?></td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */

$this->title = "Detail Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi</h3>
    </div>
    
	<div class="box-body">        

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'label'=>'Nama',
            	'format'=>'raw',
                'value'=>$model->nama	
            ],
        ],
    ]) ?>

    </div>

</div>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai</h3>
    </div>
    
    <div class="box-body">   
        <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th style="text-align:center; width:60px">No</th>
            <th style="text-align:center;">Nama</th>
            <th style="text-align:center; width:200px">NIP</th>
        </tr>
        </thead>
        <?php $i=1; foreach($model->getManyPegawai()->all() as $pegawai) { ?>
        <tr>
            <td style="text-align:center;"><?= $i; ?></td>
            <td><?= $pegawai->nama; ?></td>
            <td style="text-align:center;"><?= $pegawai->nip; ?></td>
        </tr>
        <?php $i++; } ?>
        </table>
    </div>
</div>

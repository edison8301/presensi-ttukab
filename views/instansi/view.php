<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */

$this->title = "Detail Unit Kerja";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Unit Kerja</h3>
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
            [
                'label'=>'Singkatan',
                'format'=>'raw',
                'value'=>$model->singkatan
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= $model->getLinkButtonUpdate(); ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Unit Kerja', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Sub Unit</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center; width: 30px">No</th>
                <th style="text-align: center">Nama Instansi</th>
            </tr>
            </thead>
            <?php $no = 1; ?>
            <?php foreach ($model->manySub as $sub) { ?>
                 <tr>
                     <td style="text-align: center"><?= $no++ ?></td>
                     <td><?= Html::a($sub->nama, ['/instansi/view', 'id' => $sub->id]) ?></td>
                 </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php

use app\models\Instansi;
use app\models\Jabatan;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $searchModel \app\models\InstansiSearch */

$this->title = "Struktur Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Perangkat Daerah</h3>
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

<?php /*
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Peta Jabatan</h3>
    </div>
    <div class="box-body" style="text-align: center;">
        <a href="<?= Url::to(['instansi/peta','id' => $model->id]) ?>" target="_blank">
            <?= Html::img(Url::to(['instansi/peta','id' => $model->id]),['class' => 'img-responsive', 'style' => 'margin-left:auto;margin-right:auto']) ?>
        </a>
    </div>
</div>
*/ ?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Struktur Jabatan</h3>
        <div class="box-tools">
            <button class="btn btn-sm btn-primary btn-flat" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>

    <div class="box-body">

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle">Nama Jabatan</th>
                <th style="text-align: center;">Jenis Jabatan</th>
                <th style="text-align: center; width: 50px">Nilai<br/>Jabatan</th>
                <th style="text-align: center; width: 50px">Kelas<br/>Jabatan</th>
                <th style="text-align: center; vertical-align: middle">Pegawai</th>
                <th style="text-align: center">Mutasi/<br/>Promosi</th>
            </tr>
            </thead>
            <?php foreach($model->manyJabatanKepala as $jabatan) { ?>
                <?= $this->render('_tr-jabatan', [
                    'jabatan' => $jabatan,
                    'level' => 0,
                    'searchModel' => $searchModel,
                    'id_instansi' => $model->id,
                ]); ?>
            <?php } ?>
        </table>
    </div>
</div>
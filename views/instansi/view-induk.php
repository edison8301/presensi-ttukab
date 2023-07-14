<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $searchModel \app\models\InstansiSearch */

$this->title = "Struktur Jabatan ".$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-view-jabatan',[
        'searchModel'=>$searchModel,
        'action'=>[
            '/instansi/view-jabatan',
            'id'=>$model->id
        ]
]); ?>

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
            [
                'label'=>'Singkatan',
                'format'=>'raw',
                'value'=>$model->singkatan
            ],
        ],
    ]) ?>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Peta Jabatan</h3>
    </div>
    <div class="box-body">
        <div style="margin-bottom: 20px">
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Data',[
                '/instansi-induk/create',
                'id_instansi' => $model->id
            ],[
                'class' => 'btn btn-primary btn-flat'
            ]); ?>
        </div>
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center; width:  50px">No</th>
                <th>Perangkat Daerah Induk</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th></th>
            </tr>
            <?php $i=1; foreach($model->findAllInstansiInduk() as $instansiInduk) { ?>
            <tr>
                <td style="text-align: center"><?= $i; ?></td>
                <td><?= $instansiInduk->instansiInduk->nama; ?></td>
                <td>
                    <?= Helper::getTanggalSingkat($instansiInduk->tanggal_mulai); ?>
                </td>
                <td>
                    <?= Helper::getTanggalSingkat($instansiInduk->tanggal_selesai); ?>
                </td>
                <td style="text-align: center">
                    <?= $instansiInduk->getLinkUpdateIcon(); ?>
                    <?= $instansiInduk->getLinkDeleteIcon(); ?>
                </td>

            </tr>
            <?php } ?>
        </table>
    </div>
</div>

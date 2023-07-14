<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $instansi app\models\Instansi */

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
        'model' => $instansi,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'label'=>'Nama',
            	'format'=>'raw',
                'value'=>$instansi->nama
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>

<div class="instansi-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Daftar Mesin Absensi</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Mesin Absensi', ['/absensi/mesin-absensi/create','id_instansi'=>$instansi->id], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <div class="box-body">
        <table class="table">
        <tr>
            <th style="text-align: center; width:60px">No</th>
            <th style="text-align: center;">Serialnumber</th>
            <th style="text-align: center; width:220px">FW Version</th>
            <th style="text-align: center; width:220px">Jumlah Template Fingerprint</th>
            <th style="text-align: center; width:150px">Aktivitas Terakhir</th>
            <th style="text-align: center; width:150px">Absensi Terakhir</th>
            <th style="width:100px">&nbsp;</th>
        </tr>
        <?php $i=1; foreach($instansi->getManyMesinAbsensi()->all() as $mesinAbsensi) { ?>
        <tr>
            <td style="text-align: center;"><?= $i; ?></td>
            <td><?= $mesinAbsensi->serialnumber; ?></td>
            <td style="text-align: center;"><?= $mesinAbsensi->iclock ? $mesinAbsensi->iclock->FWVersion : ""; ?></td>
            <td style="text-align: center;"><?= $mesinAbsensi->getManyTemplate()->count(); ?> Template</td>
            <td style="text-align: center;">
                <?= $mesinAbsensi->getTextLastActivity(); ?>
            </td>
            <td style="text-align: center;">
                <?= $mesinAbsensi->getTextChecktimeTerakhir(); ?>
            </td>
            <td style="text-align: center;">
                <?= Html::a('<i class="fa fa-pencil"></i>',['mesin-absensi/update','id'=>$mesinAbsensi->id]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>',['mesin-absensi/delete','id'=>$mesinAbsensi->id],['data-method'=>'post']); ?>
            </td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>


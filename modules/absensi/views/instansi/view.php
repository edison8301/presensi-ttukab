<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\components\Helper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $instansi app\models\Instansi */
/* @var $instansiSearch \app\models\InstansiSearch */

$this->title = "Detail Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search-view',[
        'instansiSearch' => $instansiSearch,
        'action' => Url::to(['view','id' => $instansi->id])
]); ?>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi</h3>
    </div>

	<div class="box-body">

    <?= DetailView::widget([
        'model' => $instansi,
        'template'=>'<tr><th style="text-align:left">{label}</th><td>{value}</td></tr>',
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

    <div class="box-body table-responsive">
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


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Absensi</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-exchange"></i> Tampilkan/Sembunyikan Rincian','#',['id'=>'btn-jam-kerja','class'=>'btn btn-primary btn-flat']); ?>
    </div>

    <div class="box-body table-responsive">
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="text-align: center; width:100px">Tanggal</th>
            <th style="text-align: center; width:150px">Hari</th>
            <th style="text-align: center">Jumlah Absensi</th>
        </tr>
        </thead>
        <?php $date = date_create(User::getTahun().'-'.$instansiSearch->bulan); ?>
        <?php $end = $date->format('t'); ?>
        <?php for($j = 1; $j <= $end; $j++) { ?>
            <?php $tanggal = $date->format('Y-m-d'); ?>
            <tr>
                <td style="text-align: center"><?= Helper::getTanggalSingkat($tanggal); ?></td>
                <td style="text-align: center"><?= Helper::getHari($tanggal); ?></td>
                <td style="text-align: center"><?= $instansi->countCheckinout(['tanggal'=>$tanggal]); ?></td>
            </tr>
            <?php $date->modify('+1 day'); ?>
        <?php } //END FOR ?>
        </table>
    </div>
</div>

<?php /*
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
            <th style="text-align:center; width:200px">Jumlah Absensi</th>
            <th style="text-align:center; width:200px">Absensi Terakhir</th>
            <th style="text-align:center; width:50px">&nbsp;</th>
        </tr>
        </thead>
        <?php $i=1; foreach($instansi->getManyPegawai()->all() as $pegawai) { ?>
        <tr>
            <td style="text-align:center;"><?= $i; ?></td>
            <td>
                <?= Html::a($pegawai->nama,['/absensi/pegawai/view','id'=>$pegawai->id]); ?><br>
                <?= $pegawai->nip; ?>
            </td>
            <td style="text-align: center"><?= $pegawai->getManyCheckinout()->count(); ?></td>
            <td style="text-align:center;"><?= $pegawai->getTextChecktimeTerakhir(); ?></td>
            <td style="text-align:center;">
                <?= Html::a('<i class="fa fa-eye"></i>',['/absensi/pegawai/view','id'=>$pegawai->id],['data-toggle'=>'tooltip','title'=>'Lihat']); ?>
            </td>
        </tr>
        <?php $i++; } ?>
        </table>
    </div>
</div>
*/ ?>

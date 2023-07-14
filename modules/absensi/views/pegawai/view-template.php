<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
use yii\widgets\DetailView;
use app\models\Pegawai;
use app\modules\absensi\models\Absensi;
use app\modules\absensi\models\KetidakhadiranJenis;

/* @var $this yii\web\View */
/* @var $pegawai app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Data Pegawai</h3>
    </div>

    <div class="box-body">

        <?= DetailView::widget([
            'model' => $pegawai,
            'template' => '<tr><th style="text-align:left">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => $pegawai->nama,
                ],
                [
                    'attribute' => 'nip',
                    'format' => 'raw',
                    'value' => $pegawai->nip,
                ],
                [
                    'attribute' => 'id_instansi',
                    'format' => 'raw',
                    'value' => $pegawai->instansi->nama,
                ],
                [
                    'attribute' => 'nama_jabatan',
                    'format' => 'raw',
                    'value' => $pegawai->nama_jabatan,
                ],
                [
                    'label'=>'Shift Kerja',
                    'value'=>$pegawai->getNamaShiftKerja()
                ]
            ],
        ]) ?>

    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Fingerprint Pegawai',['/absensi/pegawai/index-template'],['class'=>'btn btn-primary btn-flat']); ?>
    </div>
</div>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Fingerprint</h3>
    </div>

    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th style="text-align: center; vertical-align: middle; width: 60px">No</th>
            <th style="text-align: center; vertical-align: middle;">FingerID</th>
            <th style="text-align: center; vertical-align: middle;">SN</th>
            <th style="text-align: center; vertical-align: middle;">Unit Kerja</th>
        </tr>
        </thead>
        <?php $i=1; foreach($pegawai->allTemplate() as $template) { ?>
        <tr>
            <td style="text-align: center"><?= $i; ?></td>
            <td style="text-align: center"><?php print $template->FingerID; ?></td>
            <td style="text-align: center"><?php print $template->SN; ?></td>
            <td style="text-align: center"><?php print @$template->mesinAbsensi->instansi->nama; ?></td>
        </tr>
        <?php $i++; } ?>
        </table>

    </div>
</div>

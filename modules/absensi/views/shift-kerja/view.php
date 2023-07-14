<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerja */

$this->title = "Detail Shift Kerja";
$this->params['breadcrumbs'][] = ['label' => 'Shift Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="shift-kerja-view box box-primary">

    <div class="box-body">
        <?= DetailView::widget([
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'model' => $model,
            'attributes' => [
                'nama',
                'status_libur_nasional:boolean',
                [
                    'label' => 'Double Shift',
                    'value' => $model->getStringIsDoubleShift(),
                ]
            ],
        ]) ?>
    </div>

</div>

<div class="shift-kerja-view box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Jam Kerja</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jam Kerja',['/absensi/jam-kerja/create','id_shift_kerja'=>$model->id],['class'=>'btn btn-flat btn-primary']); ?>
    </div>

    <div class="box-body">
        <table class="table table-hover">
        <tr>
            <th style="text-align:center; vertical-align: middle">No</th>
            <th style="text-align:center; vertical-align: middle">Hari</th>
            <th style="text-align:center; vertical-align: middle">Jenis</th>
            <th style="text-align:center; vertical-align: middle">Nama</th>
            <th style="text-align:center; vertical-align: middle">Jam Mulai<br>Hitung</th>
            <th style="text-align:center; vertical-align: middle">Jam Minimal<br>Absensi</th>
            <th style="text-align:center; vertical-align: middle">Jam Maksimal<br>Absensi</th>
            <th style="text-align:center; vertical-align: middle">Jam Selesai<br>Hitung</th>
            <th>&nbsp;</th>
        </tr>
        <?php $i = $hari = 1; ?>
        <?php foreach($model->findAllJamKerja() as $jamKerja) { ?>
        <?php if($hari!=$jamKerja->hari) { ?>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <?php $hari = $jamKerja->hari; } ?>
        <tr>
            <td style="text-align:center"><?= $i++; ?></td>
            <td style="text-align:center"><?= Helper::getHariByInteger($jamKerja->hari); ?></td>
            <td style="text-align:center"><?= @$jamKerja->jamKerjaJenis->nama; ?></td>
            <td style="text-align:center"><?= $jamKerja->nama; ?></td>
            <td style="text-align:center"><?= $jamKerja->jam_mulai_hitung; ?></td>
            <td style="text-align:center"><?= $jamKerja->jam_minimal_absensi; ?></td>
            <td style="text-align:center"><?= $jamKerja->jam_maksimal_absensi; ?></td>
            <td style="text-align:center"><?= $jamKerja->jam_selesai_hitung; ?></td>
            <td style="text-align:center">
                <?= Html::a('<i class="fa fa-pencil"></i>',['/absensi/jam-kerja/update','id'=>$jamKerja->id]); ?>
                <?= Html::a('<i class="fa fa-trash"></i>',['/absensi/jam-kerja/delete','id'=>$jamKerja->id],['data-toggle' => 'tooltip', 'title' => 'Delete', 'data' => ['confirm' => 'Apakah Anda yakin ingin menghapus item ini?','method' => 'post']]); ?>
            </td>
        </tr>
        <?php } ?>
        </table>
    </div>

</div>

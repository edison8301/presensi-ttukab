<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerjaReguler */

$this->title = "Detail Shift Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Shift Kerja Reguler', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-reguler-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Shift Kerja Reguler</h3>
    </div>

    <div class="box-body">

    <?=DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_shift_kerja',
                'format' => 'raw',
                'value' => @$model->shiftKerja->nama,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_mulai),
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_selesai),
            ],
        ],
    ])?>

    </div>

    <div class="box-footer">
        <?=Html::a('<i class="fa fa-pencil"></i> Sunting Shift Kerja Reguler', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat'])?>
        <?=Html::a('<i class="fa fa-list"></i> Daftar Shift Kerja Reguler', ['index'], ['class' => 'btn btn-warning btn-flat'])?>
    </div>

</div>


<div class="shift-kerja-view box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Jam Kerja</h3>
    </div>
    <div class="box-header">
        <?=Html::a('<i class="fa fa-plus"></i> Tambah Jam Kerja', ['/absensi/jam-kerja-reguler/create', 'id_shift_kerja_reguler' => $model->id], ['class' => 'btn btn-flat btn-primary']);?>
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
        <?php $i = $hari = 1;
        foreach ($model->allJamKerjaReguler as $jamKerjaReguler) {?>
        <?php if ($hari != $jamKerjaReguler->hari) {?>
        <tr>
            <td colspan="9">&nbsp;</td>
        </tr>
        <?php $hari = $jamKerjaReguler->hari; } ?>
        <tr>
            <td style="text-align:center"><?=$i;?></td>
            <td style="text-align:center"><?=Helper::getHariByInteger($jamKerjaReguler->hari);?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->jamKerjaJenis ? $jamKerjaReguler->jamKerjaJenis->nama : "";?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->nama;?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->jam_mulai_hitung;?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->jam_minimal_absensi;?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->jam_maksimal_absensi;?></td>
            <td style="text-align:center"><?=$jamKerjaReguler->jam_selesai_hitung;?></td>
            <td style="text-align:center">
                <?=Html::a('<i class="fa fa-pencil"></i>', ['/absensi/jam-kerja-reguler/update', 'id' => $jamKerjaReguler->id]);?>
                <?=Html::a('<i class="fa fa-trash"></i>', ['/absensi/jam-kerja-reguler/delete', 'id' => $jamKerjaReguler->id], ['data-toggle' => 'tooltip', 'title' => 'Delete', 'data' => ['confirm' => 'Apakah Anda yakin ingin menghapus item ini?', 'method' => 'post']]);?>
            </td>
        </tr>
        <?php $i++;}?>
        </table>
    </div>

</div>

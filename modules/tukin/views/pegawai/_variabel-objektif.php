<?php

use app\modules\tukin\models\PegawaiVariabelObjektif;
use yii\helpers\Html;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */
?>
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">
            Variabel Objektif Tambahan
        </h3>
    </div>
    <div class="box-header">
        <?php if (PegawaiVariabelObjektif::accessCreate()) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Variabel Objektif', ['pegawai-variabel-objektif/create', 'id_pegawai' => $model->id, 'bulan' => $filter->bulan], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center" width="30px">No</th>
                <th style="text-align: center">Uraian</th>
                <th style="text-align: center">Masa Berlaku</th>
                <th style="text-align: center">Satuan</th>
                <th style="text-align: center">Tarif</th>
                <th style="text-align: center"></th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->getManyVariabelObjektifBulan($filter->bulan) as $variabelObjektif) { ?>
                <tr>
                    <td style="text-align: center"><?= $i++; ?></td>
                    <td><?= $variabelObjektif->refVariabelObjektif->uraian ?></td>
                    <td style="text-align: center"><?= $variabelObjektif->getRange(); ?></td>
                    <td style="text-align: center"><?= $variabelObjektif->refVariabelObjektif->satuan ?></td>
                    <td style="text-align: right"><?= Yii::$app->formatter->asInteger($variabelObjektif->tarif); ?></td>
                    <td style="text-align: center">
                        <?php if (PegawaiVariabelObjektif::accessCreate()) { ?>
                        <?= Html::a(
                            '<i class="glyphicon glyphicon-pencil"></i>',
                            ['pegawai-variabel-objektif/update', 'id' => $variabelObjektif->id],
                            ['title' => 'Ubah', 'data-toggle' => 'tooltip']
                        ) ?>
                        <?= Html::a(
                            '<i class="glyphicon glyphicon-trash"></i>',
                            ['pegawai-variabel-objektif/delete', 'id' => $variabelObjektif->id],
                            ['title' => 'Hapus', 'data' => ['toggle' => 'tooltip', 'confirm' => 'Yakin akan menghapus data?', 'method' => 'post']]
                        ) ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td style="text-align: right; font-weight: bold;" colspan="4">
                    Jumlah
                </td>
                <td style="text-align: right; font-weight: bold;">
                    <?= Helper::rp($model->getTarifVariabelObjektifBulan($filter->bulan), 0) ?>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

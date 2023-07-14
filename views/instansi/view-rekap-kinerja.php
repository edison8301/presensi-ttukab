<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $model app\models\Instansi */
/* @var $this yii\web\View */
/* @var $bulan int */

$this->title = 'Rekap Kinerja';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instansi-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Unit Kerja</h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'label' => 'Nama',
                    'format' => 'raw',
                    'value' => $model->nama
                ],
            ],
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Unit Kerja', [
            '/instansi/index-rekap-kinerja',
        ], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>

<?= $this->render('_filter-view-rekap-kinerja', [
    'model' => $model,
]) ?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Rekap Kinerja</h3>
    </div>
    <div class="box-body">
        <table class="table">
            <tr>
                <th style="text-align: center; width: 50px;">No</th>
                <th style="text-align: center; width: 100px;">Bulan</th>
                <th>Jenis</th>
                <th style="text-align: center; width: 100px;">Nilai</th>
            </tr>
            <?php $no=1; foreach ($model->findAllRekapInstansiBulan(['bulan' => $bulan]) as $rekapInstansiBulan) { ?>
                <tr>
                    <td style="text-align: center;">
                        <?= $no++; ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::getBulanLengkap($rekapInstansiBulan->bulan); ?>
                    </td>
                    <td>
                        <?= @$rekapInstansiBulan->rekapJenis->nama ?>
                    </td>
                    <td style="text-align: center">
                        <?= Helper::rp($rekapInstansiBulan->nilai, 0); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

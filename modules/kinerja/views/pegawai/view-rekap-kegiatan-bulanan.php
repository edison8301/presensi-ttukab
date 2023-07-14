<?php

use app\components\Helper;
use app\components\Session;
use app\models\InstansiPegawai;
use app\models\User;
use app\modules\kinerja\models\KegiatanHarianJenis;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $rekapHarianForm \app\modules\kinerja\models\RekapBulananForm */
/* @var $pegawai app\models\Pegawai */

$this->title = "Rekap Kegiatan Bulanan Tahun ".User::getTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Data Pegawai</h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $pegawai,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
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
            ],
        ])?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            Target Bulanan
        </h3>
    </div>
    <?php /*
    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Data', ['pegawai/rekap-kegiatan-bulanan', 'refresh' => true], ['class' => 'btn btn-success btn-flat', 'data-confirm' => 'Yakin akan melakukan refresh data?']) ?>
    </div>
    */ ?>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="text-align: center; width: 30px;">No</th>
                <th style="text-align: center;">Bulan</th>
                <th style="text-align: center;">Jumlah Kegiatan</th>
                <th style="text-align: center;">Progres</th>
                <th style="text-align: center;">Potongan Kinerja</th>
                <th style="text-align: center;"></th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($pegawai->findOrCreateAllPegawaiRekapKinerja() as $item) { ?>
                <?php $datetimeSesion = \DateTime::createFromFormat('Y-n-d', Session::getTahun().'-'.$item->bulan.'-01');
                    $lock = false;
                    if($datetimeSesion->format('Y-m') >= '2021-07') {
                        $lock = true;
                    } ?>
                <tr>
                    <td style="text-align: center;"><?= $i++ ?></td>
                    <td style="text-align: center;"><?= Helper::getBulanLengkap($item->bulan) ?></td>
                    <td style="text-align: center;">
                        <?= Html::a($item->coutKegiatanBulanan([
                            'id_kegiatan_tahunan_versi' => 1,
                        ]).' Kegiatan',[
                            '/kinerja/kegiatan-bulanan/index',
                            'KegiatanBulananSearch[bulan]'=>$item->bulan
                        ],[
                            'data-toggle'=>'tooltip',
                            'title'=>'Lihat Rincian'
                        ]); ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $lock ? 0 :  $item->getProgres() ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $lock ? 0 : $item->potongan_total ?></td>
                    <td style="text-align: center;"><?= Html::a('<i class="fa fa-eye"></i>', ['pegawai-rekap-kinerja/view', 'id' => $item->id], ['title' => 'Detail', 'data-toggle' => 'tooltip']) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

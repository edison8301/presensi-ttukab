<?php

use yii\helpers\Html;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */
/* @var $rekap \app\modules\tukin\models\PegawaiRekapTunjangan */
?>

<div class="row">
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= 100 - $rekap->getPersenAbsensi() ?></h3>
                <p>Potongan Absensi (%)</p>
            </div>
            <div class="icon">
                <i class="fa fa-clock"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= 100 - $rekap->getPersenKinerja() ?></h3>
                <p>Potongan Kinerja (%)</p>
            </div>
            <div class="icon">
                <i class="fa fa-list"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?= $model->getCountManyVariableObjektifBulan($filter->bulan) ?></h3>
                <p>Jumlah Variabel Objektif</p>
            </div>
            <div class="icon">
                <i class="fa fa-list"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?= $model->getCountHukumanDisiplin($filter->bulan) ?></h3>
                <p>Jumlah Hukuman Disiplin</p>
            </div>
            <div class="icon">
                <i class="fa fa-gavel"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
</div>
<div class="row">
    <?php /*
    <div class="col-sm-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= Helper::rp($model->getRupiahTukin()) ?></h3>
                <p>Jumlah Tunjangan (100%)</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar-sign"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    */ ?>
    <div class="col-sm-3">
        <div class="small-box bg-fuchsia">
            <div class="inner">
                <h3><?= Helper::rp(@$model->getTarifVariabelObjektifBulan($filter->bulan), 0) ?></h3>
                <p>Jumlah Tunjangan Variabel Objektif</p>
            </div>
            <div class="icon">
                <i class="fa fa-list"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <?php if ($model->getIsInstansiKordinatif($filter->bulan)) { ?>
        <div class="col-sm-3">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3><?= Helper::rp(@$model->getRupiahInstansiKordinatif($filter->bulan), 0) ?></h3>
                    <p>Jumlah Tunjangan Instansi Kordinatif</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bank"></i>
                </div>
                <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
            </div>
        </div>
    <?php } ?>
    <?php /*
    <div class="col-sm-3">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= Helper::rp(@$model->getRupiahTukinPersen($filter->bulan) - @$model->getTarifVariabelObjektifBulan($filter->bulan), 0) ?></h3>
                <p>Jumlah Tunjangan Diterima</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar-sign"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    */ ?>

    <?php /*
    <div class="col-sm-3">
        <div class="small-box bg-light-blue">
            <div class="inner">
                <h3><?= Helper::rp(@$model->getRupiahAkhirPersen($filter->bulan), 0) ?></h3>
                <p>Jumlah Tunjangan Total</p>
            </div>
            <div class="icon">
                <i class="fa fa-list"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    */ ?>
</div>

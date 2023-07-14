<?php

use app\models\Pegawai;
use app\modules\tukin\models\KelasJabatan;
use yii\helpers\Html;
use app\modules\tukin\models\Jabatan;
use app\modules\tukin\models\Eselon;
use miloschuman\highcharts\Highcharts;
/* @var $this \yii\web\View */

$this->title = "Dasbor Tukin";
?>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= Jabatan::countStruktural() ?></h3>
                <p>Jabatan Struktural</p>
            </div>
            <div class="icon">
                <i class="fa fa-sitemap"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 1]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-fuchsia">
            <div class="inner">
                <h3><?= Jabatan::countNonStruktural() ?></h3>
                <p>Jabatan Non Struktural</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index', 'JabatanSearch' => ['id_jenis_jabatan' => 2]], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= Jabatan::countTotal() ?></h3>
                <p>Total Jabatan</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index'], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= Pegawai::find()->aktif()->count(); ?></h3>
                <p>Total Pegawai</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <?= Html::a('Detail <i class="fa fa-arrow-circle-right"></i>', ['jabatan/index'], ['class' => 'small-box-footer', 'style' => 'text-align: left; padding-left: 5px']) ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="box box-success">
            <div class="box-header with-border">
                Daftar Kelas Jabatan Struktural
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align: center;" width="50px">No</th>
                        <th style="text-align: center;">Kelas Jabatan</th>
                        <th style="text-align: center;">Persediaan Pegawai</th>
                    </tr>
                    </thead>
                    <?php $i = 1; ?>
                    <?php foreach (KelasJabatan::find()->orderBy(['kelas_jabatan' => SORT_DESC])->all() as $kelasJabatan) { ?>
                        <tr>
                            <td style="text-align: center"><?= $i++ ?></td>
                            <td style="text-align: center"><?= Html::a($kelasJabatan->kelas_jabatan, ['pegawai/index', 'PegawaiSearch' => ['kelas_jabatan' => $kelasJabatan->kelas_jabatan]]) ?></td>
                            <td style="text-align: center"><?= $kelasJabatan->countPegawai() ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Jenis jabatan</h3>
            </div>
            <div class="box-body">
                <?= Highcharts::widget([
                    'options' => [
                        'credits' => false,
                        'title' => ['text' => 'GRAFIK JENIS JABATAN'],
                        'exporting' => ['enabled' => true],
                        'plotOptions' => [
                            'pie' => [
                                'cursor' => 'pointer',
                            ],
                        ],
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => 'Jumlah Jabatan',
                                'data' => Jabatan::getGrafikList(),
                            ],
                        ],
                    ],
                ]);?>
                <?= Highcharts::widget([
                    'options' => [
                        'credits' => false,
                        'title' => ['text' => 'GRAFIK ESELON JABATAN'],
                        'exporting' => ['enabled' => true],
                        'plotOptions' => [
                            'pie' => [
                                'cursor' => 'pointer',
                            ],
                        ],
                        'legend' => true,
                        'series' => [
                            [
                                'type' => 'pie',
                                'name' => 'Jumlah Pegawai',
                                'data' => Eselon::getGrafikList(),
                            ],
                        ],
                    ],
                ]);?>
            </div>
        </div>
    </div>
</div>

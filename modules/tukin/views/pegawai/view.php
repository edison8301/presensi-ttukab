<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\Helper;
use app\modules\tukin\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$rekap = $model->findOrCreatePegawaiRekapTunjangan($filter->bulan);
?>
<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => $model->nip,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->getNamaInstansi(),
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => @$model->getNamaJabatan(),
            ],
            [
                'attribute' => 'id_atasan',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->atasan->nama,
            ],
            [
                'label' => 'Kelas Jabatan',
                'value' => @$model->jabatan->kelas_jabatan
            ],
            [
                'label' => 'Nilai Jabatan',
                'value' => @$model->kelasJabatan->getNilaiTengah()
            ],
            [
                'label' => 'Jumlah Tunjangan (100%)',
                'value' => Helper::rp($model->getRupiahTukin()),
            ],
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?php if (User::isAdmin()) { ?>
            <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?php } ?>
    </div>
</div>


<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Filter Bulan
        </h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'layout' => 'inline',
            'action' => User::isAdmin() ? Url::to(['pegawai/view', 'id' => $model->id]) : Url::to(['pegawai/profil']),
            'method' => 'get',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]); ?>

        <?= $form->field($filter, 'bulan')
            ->dropDownList(\app\components\Helper::getBulanList(),['prompt'=>'- Filter Bulan -']) ?>

        <div class="form-group">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Rincian Perhitungan TPP
        </h3>
    </div>
    <div class="box-body">
        Dalam Proses Penyesuaian
    </div>
</div>

<?php /*
<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rekap TPP Pegawai</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Potongan Disiplin Total</p>
                        <h3>0 %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Potongan Disiplin Kehadiran</p>
                        <h3>0 %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Potongan Disiplin Kegiatan</p>
                        <h3>0 %</h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
        </div>
    </div><!-- .box-body -->
</div>

<div class="pegawai-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rincian Potongan Total</h3>
    </div>
    <div class="box-body table-responsive">
        <?php $i = 0 ?>
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center; width:60px">No</th>
                <th>Uraian</th>
                <th style="text-align: center">Kontribusi (%)</th>
                <th style="text-align: center">Potongan (%)</th>
                <th style="text-align: center">Potongan Total (%)</th>
            </tr>
            <tr>
                <th style="text-align: center">1</th>
                <th>Potongan Disiplin</th>
                <td style="text-align: center">40%</td>
                <td style="text-align: center">0%</td>
                <td style="text-align: center">0%</td>
            </tr>
            <tr>
                <th style="text-align: center">2</th>
                <th>Potongan Kinerja</th>
                <td style="text-align: center">60%</td>
                <td style="text-align: center">0%</td>
                <td style="text-align: center">0%</td>
            </tr>
            <tr>
                <th></th>
                <th>Total</th>
                <td style="text-align: center">100%</td>
                <td style="text-align: center"></td>
                <td style="text-align: center">0%</td>
            </tr>
        </table>
    </div>
</div>
*/ ?>


<?php //$this->render('_rekap', ['model' => $model, 'filter' => $filter, 'rekap' => $rekap]) ?>

<?php //$this->render('_rekap-kinerja', ['rekap' => $rekap]) ?>

<?php //$this->render('_rekap-absensi', ['rekap' => $rekap]) ?>

<?php //$this->render('_variabel-objektif', ['filter' => $filter, 'model' => $model]) ?>

<?php /* if ($model->instansi->getHasKordinatifAktif($rekap->bulan)) {
    echo $this->render('_instansi-kordinatif', ['model' => $model, 'rekap' => $rekap]);
} */ ?>

<?php /* $this->render('_tunjangan', ['filter' => $filter, 'rekap' => $rekap, 'model' => $model]) */ ?>

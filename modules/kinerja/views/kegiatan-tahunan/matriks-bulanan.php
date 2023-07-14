<?php

use app\components\Helper;
use yii\helpers\Html;
use app\models\InstansiPegawai;
use app\modules\kinerja\models\KegiatanTahunan;
use app\models\User;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model KegiatanTahunan */
/* @var $bulan integer */
/* @var $allKegiatanTahunanInduk KegiatanTahunan[] */
/* @var $id_instansi_pegawai int */

$this->title = "Matriks Target Bulan " . Helper::getBulanLengkap($bulan) . " Tahun ". User::getTahun();
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php /*
<?= AlertKegiatan::widget(['kegiatan' => $model]); ?>
*/ ?>

<?= $this->render('//filter/_filter-tahun'); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Matriks Target Bulanan</h3>
    </div>
    <div class="box-header">
        <?php $form = ActiveForm::begin([
            'action' => ['matriks-bulanan'],
            'method' => 'get',
        ]); ?>
        <?php if (Yii::$app->user->identity->pegawai->getHasMutasi()): ?>
        <div class="row">
            <div class="col-sm-1" style="text-align: right; top: 6px">
                <?= Html::label('Jabatan') ?>
            </div>
            <div class="col-sm-5">
                <?= Select2::widget([
                    'name' => 'id_instansi_pegawai',
                    'value' => $id_instansi_pegawai,
                    'data' => InstansiPegawai::getListInstansi(User::getIdPegawai(), true),
                    'options' => [
                        'placeholder' => '- Pilih Instansi -',
                        'onchange' => 'this.form.submit()',
                    ]
                ]); ?>
            </div>
        </div>
        <?php endif ?>
        <div class="row">
            <div class="col-sm-1" style="text-align: right; top: 6px">
                <?= Html::label('Bulan') ?>
            </div>
            <div class="col-sm-2">
                <?= Select2::widget([
                    'name' => 'bulan',
                    'value' => $bulan,
                    'data' => Helper::getBulanList(),
                    'options' => [
                        'placeholder' => '- Pilih Bulan -',
                        'onchange' => 'this.form.submit()',
                    ]
                ]); ?>
            </div>
        </div>
        </div>
        <?php ActiveForm::end(); ?>
    <div class="box-header">

    </div>
    <div class="box-body" style="overflow: auto;">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th style="vertical-align:middle;text-align: left;" rowspan="2">Kegiatan/Tahapan</th>
                <th style="vertical-align:middle;text-align: center; width:80px" rowspan="2">Satuan</th>
                <th style="vertical-align:middle;text-align: center" colspan="2">Target / Realisasi Bulan <?= Helper::getBulanLengkap($bulan) ?> </th>
                <th style="vertical-align:middle;text-align:center; width:30px" rowspan="2">Status</th>
            </tr>
            <tr>
                <th style="text-align:center; width:130px">Target</th>
                <th style="text-align:center; width:130px">Realisasi</th>
            </tr>
            </thead>
            <?php $id_induk = null ?>
            <?php foreach ($allKegiatanTahunanInduk as $kegiatanTahunan) {
                $level = 1;
                if ($kegiatanTahunan->induk !== null) {
                    if ($id_induk !== $kegiatanTahunan->induk->id) {
                        $id_induk = $kegiatanTahunan->induk->id;
                        echo $this->render('_tr-matriks-bulanan', ['level' => $level, 'bulan' => $bulan, 'kegiatanTahunan' => $kegiatanTahunan->induk, 'readOnly' => true]);
                    }
                    $level++;
                }
                echo $this->render('_tr-matriks-bulanan', ['kegiatanTahunan' => $kegiatanTahunan, 'level' => $level, 'bulan' => $bulan, 'readOnly' => false]);
            } ?>
        </table>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
    </div>
</div>

<?php /*
<?= $this->render('_kegiatan-bulanan', ['model' => $model]); ?>
<?= $this->render('_kegiatan-harian', ['model' => $model]); ?>
*/ ?>

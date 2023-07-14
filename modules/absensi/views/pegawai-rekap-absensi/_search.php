<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiRekapAbsensiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-rekap-absensi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_pegawai') ?>

    <?= $form->field($model, 'bulan') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'id_instansi') ?>

    <?php // echo $form->field($model, 'id_golongan') ?>

    <?php // echo $form->field($model, 'jumlah_hari_kerja') ?>

    <?php // echo $form->field($model, 'jumlah_hadir') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir') ?>

    <?php // echo $form->field($model, 'jumlah_izin') ?>

    <?php // echo $form->field($model, 'jumlah_sakit') ?>

    <?php // echo $form->field($model, 'jumlah_cuti') ?>

    <?php // echo $form->field($model, 'jumlah_tugas_belajar') ?>

    <?php // echo $form->field($model, 'jumlah_tugas_kedinasan') ?>

    <?php // echo $form->field($model, 'jumlah_dinas_luar') ?>

    <?php // echo $form->field($model, 'jumlah_tanpa_keterangan') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir_apel_pagi') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir_apel_sore') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir_upacara') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir_senam') ?>

    <?php // echo $form->field($model, 'jumlah_tidak_hadir_sidak') ?>

    <?php // echo $form->field($model, 'persen_potongan_fingerprint') ?>

    <?php // echo $form->field($model, 'persen_potongan_kegiatan') ?>

    <?php // echo $form->field($model, 'persen_potongan_total') ?>

    <?php // echo $form->field($model, 'status_kunci') ?>

    <?php // echo $form->field($model, 'waktu_diperbarui') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

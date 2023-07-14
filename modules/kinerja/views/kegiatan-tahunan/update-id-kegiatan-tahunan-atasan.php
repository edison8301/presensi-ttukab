<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use app\models\Pegawai;
use app\models\InstansiPegawai;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanTahunan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

$this->title = "Ubah Kegiatan Tahunan Atasan";

?>

<?php $form = ActiveForm::begin([
    'type'=>'horizontal',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="kegiatan-tahunan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Tahunan Atasan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaNipPegawai')->textInput(['readonly' => 'readonly']) ?>

        <?= $form->field($model, 'nomorSkpLengkap',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-4',
                'error' => '',
                'hint' => '',
            ]
        ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Nomor SKP") ?>

        <?php if($model->id_induk==null AND \app\components\Session::getTahun() < 2021) { ?>
            <?= $form->field($model, 'nomor_skp',[
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->dropDownList(InstansiPegawaiSkp::getListNomor()) ?>
        <?php } ?>

        <?php if($model->id_induk!=null AND \app\components\Session::getTahun() < 2021) { ?>
            <?= $form->field($model, 'nomorSkpLengkap',[
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Nomor SKP") ?>
        <?php } ?>

        <?php if($model->id_induk!=null) { ?>
            <?= $form->field($model, 'namaKegiatanInduk',[
                'horizontalCssClasses' => [
                    'label' => 'col-md-2',
                    'wrapper' => 'col-md-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Kegiatan Induk") ?>
        <?php } ?>

        <?= $form->field($model, 'nama')->textArea(['rows' => 5, 'readonly' => 'readonly']) ?>

        <?php if($model->isVisibleStatusKegiatanTahunanAtasan() == true) { ?>
            <?= $form->field($model, 'status_kegiatan_tahunan_atasan')->dropDownList(
                ['1' => 'Mendukung Kegiatan Atasan','0'=>'Tidak Mendukung Kegiatan Atasan'],
                ['id' => 'status-kegiatan-tahunan-atasan']
            )->label('Status Dukungan Kegiatan Atasan'); ?>
            <?php $this->registerJs("
                $(document).ready(function() {
                    let nilai = $('#status-kegiatan-tahunan-atasan').val();
                    if(nilai != '1') {
                        $('#id-kegiatan-tahunan-atasan').hide();
                    }
                });
                $('#status-kegiatan-tahunan-atasan').on('change', function() {
                    let nilai = $(this).val();
                    if(nilai == '1') {
                        $('#id-kegiatan-tahunan-atasan').show('slow');
                    } else {
                        $('#id-kegiatan-tahunan-atasan').hide('slow');
                    }
                });
            ", View::POS_READY); ?>
        <?php } ?>

        <?php if($model->isVisibleIdKegiatanTahunanAtasan() == true) { ?>
        <div id="id-kegiatan-tahunan-atasan">
            <?php print $form->field($model, 'id_kegiatan_tahunan_atasan')->dropDownList(
                $model->getListKegiatanTahunanAtasan(),
                ['prompt' => 'Pilih Kegiatan Tahunan Atasan']
            )->label('Kegiatan Atasan yang Didukung'); ?>
            <?php /* $form->field($model, 'id_kegiatan_tahunan_atasan')->widget(Select2::className(), [
                'data' => $model->getListKegiatanTahunanAtasan(),
                'options' => [
                    'placeholder' => '- Pilih Kegiatan Tahunan Atasan -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ])->label('Kegiatan Atasan yang Didukung'); */ ?>
        </div>
        <?php } ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

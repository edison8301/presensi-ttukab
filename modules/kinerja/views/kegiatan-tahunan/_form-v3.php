<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanAspek;
use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use app\models\RpjmdIndikatorFungsional;
use app\models\RpjmdKegiatanIndikator;
use app\models\RpjmdSasaranIndikator;
use app\models\RpjmdProgramIndikator;
use app\models\RpjmdSubkegiatanIndikator;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTahunan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

$butirKegiatanJf = 'butir_kegiatan_jf[0]';
$outputJf = 'output_jf[0]';
$angkaKreditJf = 'angka_kredit_jf[0]';

?>

<?php $form = ActiveForm::begin([
    //'type'=>'horizontal',
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
        <h3 class="box-title">Form Indikator Kinerja Individu</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php if ($model->isJpt() == false) { ?>
            <?= $form->field($model, 'id_kegiatan_aspek')->dropDownList(KegiatanAspek::getList(), [
                'prompt' => '- Pilih Aspek -',
            ]) ?>
        <?php } ?>

        <?php if ($model->id_kegiatan_tahunan_jenis == KegiatanTahunan::UTAMA) { ?>
            <?= $this->render('_input-indikator-renstra', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        <?php } ?>

        <?= $form->field($model, 'nama')->textArea(['rows' => 5])->label('Indikator Kinerja Individu') ?>

        <?= $form->field($model, 'target')->textInput() ?>

        <?= $form->field($model, 'satuan')->textInput() ?>

        <?php if ($model->isJpt()) { ?>
            <?= $form->field($model, 'perspektif')->widget(Select2::className(), [
                'data' => KegiatanTahunan::getListPerspektif(),
                'options' => [
                    'placeholder' => '- Pilih Perspektif -',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>
        <?php } ?>

        <?= Html::hiddenInput('referrer', $referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-12">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

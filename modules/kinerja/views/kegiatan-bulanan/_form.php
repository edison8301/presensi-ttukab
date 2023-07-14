<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\components\Helper;
use app\models\KegiatanTahunan;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulanan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

?>

<?php $form = ActiveForm::begin([
    'layout'=>'horizontal',
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

<div class="kegiatan-bulanan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Bulanan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php if ($model->id_kegiatan_tahunan === null): ?>
            <?= $form->field($model, 'id_kegiatan_tahunan')->widget(Select2::className(), [
                'data' => KegiatanTahunan::getList(),
                'options' => [
                    'placeholder' => '- Pilih Kegiatan Tahunan -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>
        <?php endif ?>

        <?= $form->field($model, 'bulan')->widget(Select2::className(), [
                'data' => Helper::getBulanList(),
                'options' => [
                    'placeholder' => '- Pilih Bulan -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>

        <?= $form->field($model, 'target_kuantitas')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

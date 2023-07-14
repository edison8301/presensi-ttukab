<?php

use app\components\Session;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanHarianDiskresi */
/* @var $form yii\widgets\ActiveForm */

$datetime = \DateTime::createFromFormat('Y-n', Session::getTahun() . '-12');

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

<div class="kegiatan-harian-diskresi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Harian Diskresi</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
            'data' => \app\models\Pegawai::getList(),
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
            ]
        ]) ?>

        <?= $form->field($model, 'tanggal')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal', 'autocomplete' => 'off'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'startDate' => $datetime->format('Y-01-01'),
                'endDate' => $datetime->format('Y-m-t'),
            ]
        ]) ?>

        <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

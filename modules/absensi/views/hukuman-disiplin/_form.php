<?php

use app\modules\absensi\models\HukumanDisiplinJenis;
use app\models\Pegawai;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\HukumanDisiplin */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */
?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="hukuman-disiplin-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Hukuman Disiplin</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaHukumanDisiplinJenis')->textInput(['readonly' => 'readonly']) ?>

        <?php /* $form->field($model, 'id_hukuman_disiplin_jenis')->widget(Select2::className(), [
            'data' => HukumanDisiplinJenis::getList(),
            'options' => [
                'placeholder' => '- Pilih Jenis -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); */ ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
            'data' => Pegawai::getList(),
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>


        <?php if($model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN) { ?>
            <?= $form->field($model, 'bulan')->widget(Select2::className(), [
                'data' => Helper::getListBulan(),
                'options' => [
                    'placeholder' => '- Pilih Bulan -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>

            <?= $form->field($model, 'tahun')->textInput(['readonly' => 'readonly']) ?>
        <?php } ?>

        <?php if($model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::SEDANG
            OR $model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::BERAT
        ) { ?>
        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Mulai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'tanggal_selesai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>
        <?php } ?>

        <?= $form->field($model, 'keterangan')->textarea(['rows' => 5]) ?>

        <?= Html::hiddenInput('referrer', $referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php

use app\modules\tukin\models\Pegawai;
use app\modules\tunjangan\models\TunjanganPotongan;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganPegawai */
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

<div class="tunjangan-potongan-pegawai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Tunjangan Potongan Pegawai</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_tunjangan_potongan')->widget(Select2::className(), [
            'data' => TunjanganPotongan::getList(),
            'options' => [
                'placeholder' => '- Pilih Jenis Potongan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
            'data' => Pegawai::getList(),
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?php /*
        <?= $form->field($model, 'id_pegawai')->widget(Select2::className(), [
            'options' => [
                'placeholder' => '- Pilih Pegawai -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 3,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
               'ajax' => [
                    'url' => Url::to(['/pegawai/get-list-ajax']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
            ]
        ]); ?>
        */ ?>

        <?= $form->field($model, 'tanggal_mulai')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal Selesai'],
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

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php

use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\modules\tukin\models\Pegawai;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\PegawaiVariabelObjektif */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */
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

<div class="pegawai-variabel-objektif-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai Variabel Objektif</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_pegawai')->widget(Select2::class, [
            'data' => Pegawai::getList(User::getIdInstansi()),
            'options' => [
                'placeholder' => '- Pilih Variabel Objektif -',
            ],
        ]) ?>

        <?= $form->field($model, 'variabel_objektif')
            ->textInput(['readonly' => true])
            ->hint($this->render('_modalVariabelObjektif', ['model' => $model])) ?>

        <?= $form->field($model, 'bulan_mulai')->dropDownList(Helper::getBulanList()) ?>

        <?= $form->field($model, 'bulan_selesai')->dropDownList(Helper::getBulanList()) ?>

        <?= $form->field($model, 'tarif')->textInput() ?>

        <?= $form->field($model, 'id_ref_variabel_objektif')->hiddenInput()->label(false); ?>

        <?= Html::hiddenInput('referrer', $referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

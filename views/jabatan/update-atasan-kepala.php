<?php

use app\models\InstansiBidang;
use app\models\Jabatan;
use app\models\JabatanEselon;
use app\models\JabatanEvjab;
use app\modules\tukin\models\Pegawai;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Jabatan */
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

<div class="jabatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model) ?>

        <?= $form->field($model, 'namaInstansi')->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Perangkat Daerah") ?>

        <?= $form->field($model, 'nama')->textArea([
            'rows' => 3,
            'readonly' => 'readonly'
        ]) ?>

        <?php echo $form->field($model, 'id_induk')->widget(Select2::class, [
                'data' => Jabatan::getListKepala(),
                'options' => ['prompt' => '- Pilih Induk -'],
                'pluginOptions' => ['allowClear' => true]
        ]) ?>


        <?= Html::hiddenInput('referrer',$referrer) ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

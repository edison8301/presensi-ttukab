<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\PetaJenis;
use kartik\select2\Select2;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $model app\models\Peta */
/* @var $form yii\widgets\ActiveForm */
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

<div class="peta-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Import SMP</h3>
    </div>
	<div class="box-body">
        
        <?= $form->field($model, 'keterangan')->textarea(['rows' => '20','style' => 'width: 800px;']) ?>              
        
	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

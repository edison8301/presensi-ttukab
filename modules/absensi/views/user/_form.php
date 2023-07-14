<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\kinerja\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<div class="user-form box box-primary">

    <div class="box-body">
	    <?= $form->field($model, 'kode_pegawai')->textInput() ?>

	    <?= $form->field($model, 'kode_unit_kerja')->dropDownList(
	            \app\modules\kinerja\models\UnitKerja::getList()
	    ) ?>
	</div>

    
    <div class="box-footer">
        <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class'=>'btn btn-success btn-flat']) ?>
    </div>


</div>

<?php ActiveForm::end(); ?>

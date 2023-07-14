<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\kinerja\models\UnitKerja;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\FilterForm */
/* @var $referrer string */

$this->title = "Set Pokok Tunjangan";

?>

<?php $form = ActiveForm::begin([
        'layout'=>'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-4',
                'error' => '',
                'hint' => '',
            ],
        ]
]); ?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $model->nama; ?></h3>
	</div>
	<div class="box-body">
		<?= $form->field($model, 'grade')->dropDownList(
				\app\modules\kinerja\models\GradeTunjangan::getList()
		) ?>
		<?= Html::hiddenInput('referrer',$referrer); ?>
	</div>

	<div class="box-footer">
		<?= Html::submitButton('<i class="fa fa-check"></i> Simpan', ['class' => 'btn btn-success btn-flat']) ?>
	</div>
</div>

<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $model app\models\FilterForm */

?>


<?php

$filter = new \app\models\FilterForm;

if($filter->load(Yii::$app->request->get()))
{
	$filter->setSession();
}

$filter->bulan = User::getBulan();
$filter->tahun = User::getTahun();
$filter->kode_instansi = User::getKodeInstansi();

?>

<?php $form = ActiveForm::begin([
        'layout'=>'inline',
        'method'=>'get',
        'action'=>['/absensi/default/index'],
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
		<h3 class="box-title">Filter Tahun</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<?= $form->field($filter, 'bulan')->dropDownList([
					'1' => 'Jan', '2' => 'Feb','3' => 'Mar', '4' => 'Apr','5'=>'Mei',
					'6'=>'Jun','7'=>'Jul','8'=>'Agt','9'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Des'
				]) ?>
				<?= $form->field($filter, 'tahun')->textInput(['maxlength' => true, 'type' => 'number']) ?>
				<?= $form->field($filter, 'kode_instansi')->dropDownList(
						Instansi::getList(),
						['prompt'=>'- SEMUA OPD -']
				); ?>

				<?= Html::submitButton('<i class="fa fa-check"></i> Filter', ['class' => 'btn btn-success btn-flat']) ?>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>

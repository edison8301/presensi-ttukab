<?php

use app\models\Jabatan;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jabatan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */
/* @var $instansiAtasan \app\models\Instansi */

$this->title = "Ubah Atasan Kepala";
$this->params['breadcrumbs'][] = ['label' => 'Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
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

<div class="jabatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textarea(['rows' => 3,'readonly'=>'readonly']) ?>

        <?= $form->field($model, 'id_induk')->dropDownList(
            Jabatan::getListStruktur($instansiAtasan->id),
            ['prompt' => '- Pilih Atasan Langsung -']
        )->hint($instansiAtasan->nama) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

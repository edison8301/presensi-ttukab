<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\InstansiJenis;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'layout'=>'inline',
    'action'=>['/instansi/index'],
    'method'=>'get',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
]); ?>

<div class="instansi-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Filter Unit Kerja</h3>
    </div>
    <div class="box-body">

        <?= $form->field($searchModel, 'id_induk')->dropDownList(Instansi::getListInduk(),['prompt'=>'- Pilih Unit Kerja Induk -']) ?>

        <?= $form->field($searchModel, 'id_instansi_jenis')->dropDownList(InstansiJenis::getList(),['prompt'=>'- Pilih Jenis Unit Kerja -']) ?>

        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>

    </div>

</div>

<?php ActiveForm::end(); ?>
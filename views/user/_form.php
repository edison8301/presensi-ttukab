<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Pegawai;
use app\models\Instansi;
use app\models\Grup;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
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

<div class="box box-primary user-form">
    <div class="box-header with-border">
        <h3 class="box-title">Form user</h3>
    </div>
    <div class="box-body">

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        <?php } ?>
        
        <?php if($model->visibleIdPegawai()) { ?>
        <?= $form->field($model, 'id_pegawai')->dropDownList(Pegawai::getList(),['prompt'=>'- Pilih Pegawai -']) ?>
        <?php } ?>

        <?php if($model->visibleIdInstansi()) { ?>
        <?= $form->field($model, 'id_instansi')->dropDownList(Instansi::getList(),['prompt'=>'- Pilih Unit Kerja -']) ?>
        <?php } ?>

        <?php if($model->visibleIdGrup()) { ?>
        <?= $form->field($model, 'id_grup')->dropDownList(Grup::getList(),['prompt'=>'- Pilih Grup -']) ?>
        <?php } ?>

        

    </div>
    <div class="box-footer with-border">
        <div class="col-sm-3 col-sm-offset-2">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

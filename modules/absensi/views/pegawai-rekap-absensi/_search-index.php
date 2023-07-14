<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\select2\Select2;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */
/* @var $pegawaiRekapAbsensiSearch \app\modules\absensi\models\PegawaiRekapAbsensiSearch */

?>

<?= $this->render('//filter/_filter-tahun') ?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Pencarian</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($pegawaiRekapAbsensiSearch, 'id_instansi')->widget(Select2::className(), [
                    'data' => Instansi::getList(),
                    'options' => [
                        'placeholder' => '- Pilih Instansi -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($pegawaiRekapAbsensiSearch, 'bulan')->dropDownList(
                    Helper::getListBulan(),
                    [
                        'prompt'=>'- Pilih Bulan -',
                    ]
                )->label(false); ?>
            </div>
            <div class="col-sm-3">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>

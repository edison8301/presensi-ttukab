<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Instansi;
use app\models\InstansiSearch;

/* @see \app\modules\absensi\controllers\KetidakhadiranPanjangController::actionIndex() */
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\absensi\models\KetidakhadiranPanjangSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'method' => 'get',
]); ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
                    'data' => Instansi::getList(),
                    'options' => [
                        'placeholder' => '- Pilih Instansi -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '100%',
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($searchModel, 'bulan')->dropDownList(
                    Helper::getListBulan(),
                    [
                        'prompt'=>'- Pilih Bulan -',
                    ]
                )->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($searchModel, 'tanggal_mulai_awal')->widget(DatePicker::className(), [
                    'removeButton' => false,
                    'options' => ['placeholder' => 'Tanggal Awal'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($searchModel, 'tanggal_mulai_akhir')->widget(DatePicker::className(), [
                    'removeButton' => false,
                    'options' => ['placeholder' => 'Tanggal Akhir'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

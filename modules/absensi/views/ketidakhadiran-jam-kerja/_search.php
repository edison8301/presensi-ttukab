<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Pegawai;
use app\models\InstansiSearch;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Pencarian</h3>
    </div>

    <div class="box-body">
        <div class="row col-sm-5">
            <?= $form->field($searchModel, 'id_pegawai')->widget(Select2::class, [
                // 'data' => Pegawai::getList(),
                'initValueText' => @$searchModel->pegawai->nama !== null ? @$searchModel->pegawai->nama : null,
                'language' => 'id',
                'options' => [
                    'placeholder' => '- Pilih Pegawai -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => Yii::$app->params['select2.minimumInputLength'],
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/pegawai/get-list-ajax']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) {
                            return {q:params.term};
                        }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) {
                        return markup;
                    }'),
                    'templateResult' => new JsExpression('function(pegawai) {
                        return pegawai.text;
                    }'),
                    'templateSelection' => new JsExpression('function (pegawai) {
                        return pegawai.text;
                    }'),
                ]
            ])->label(false); ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($searchModel, 'bulan')->dropDownList(
                Helper::getListBulan(),
                [
                    'prompt'=>'- Pilih Bulan -',
                ]
            )->label(false) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>

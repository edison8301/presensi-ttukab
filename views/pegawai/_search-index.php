<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Instansi;
use app\models\InstansiSearch;

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
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
                    'data' => Instansi::getList(),
                    'options' => [
                        'style' => 'width: 500px',
                        'placeholder' => '- Pilih Instansi -',
                        'id' => 'id-instansi',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-3">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>

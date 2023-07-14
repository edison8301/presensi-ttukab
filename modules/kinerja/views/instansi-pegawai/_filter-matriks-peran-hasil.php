<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\select2\Select2;
use app\models\Instansi;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

?>

<?php $form = ActiveForm::begin([
    'action' => Yii::$app->request->url,
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
                    'data' => Instansi::getList(),
                    'options' => [
                        'placeholder' => '- Pilih Perangkat Daerah -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

<?php

use app\modules\kinerja\models\PegawaiRekapKinerja;
use kartik\select2\Select2;
use app\modules\kinerja\models\PegawaiRekapKinerjaSearch;
use kartik\form\ActiveForm;
use app\models\Instansi;
use yii\helpers\Html;
use app\components\Helper;

/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 10/18/2018
 * Time: 10:58 AM
 */

/* @var $this \yii\web\View */
/* @var $searchModel PegawaiRekapKinerjaSearch  */
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
            <div class="col-sm-4">
                <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
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
                <?= $form->field($searchModel, 'bulan')->dropDownList(
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


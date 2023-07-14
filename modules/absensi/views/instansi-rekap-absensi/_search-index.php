<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Instansi;
use app\models\InstansiSearch;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $instansiRekapAbsensiSearch \app\modules\absensi\models\InstansiRekapAbsensiSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'layout'=>'inline',
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Pencarian</h3>
    </div>

    <div class="box-body">
        <?= $form->field($instansiRekapAbsensiSearch, 'bulan')->dropDownList(
            Helper::getListBulan(),
            [
                'prompt'=>'- Pilih Bulan -',
            ]
        ); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>

    </div>

</div>
<?php ActiveForm::end(); ?>

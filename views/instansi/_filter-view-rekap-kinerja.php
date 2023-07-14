<?php

use app\components\Helper;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin([
    'action' => ['/instansi/view-rekap-kinerja', 'id' => $model->id],
    'type'=>'inline',
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Bulan</h3>
    </div>

    <div class="box-body">
        <?= Html::dropDownList('bulan', $model->bulan, Helper::getListBulan(), [
            'class' => 'form-control',
        ]) ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>
<?php ActiveForm::end(); ?>

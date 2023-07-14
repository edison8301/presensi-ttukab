<?php

use app\components\Helper;
use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $action string */

?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'type'=>'inline',
    'method' => 'get',
]); ?>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Bulan</h3>
    </div>

    <div class="box-body">
        <?= $form->field($searchModel, 'bulan',[
            'addon' => ['prepend' => ['content'=>'Bulan']]
        ])->dropDownList(
            Helper::getListBulan(),
            [
                'prompt'=>'- Pilih Bulan -',
            ]
        ); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>

    </div>

</div>
<?php ActiveForm::end(); ?>

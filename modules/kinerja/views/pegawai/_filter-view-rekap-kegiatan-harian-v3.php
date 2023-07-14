<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $rekapHarianForm \app\modules\kinerja\models\RekapHarianForm */
/* @var $model \app\modules\tukin\models\Pegawai */
/* @var $action array */

if (@$action == null) {
    $action = [
        '/kinerja/pegawai/view-rekap-kegiatan-harian-v3',
        'id' => $model->id,
    ];
}

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Filter</h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'type' => 'inline',
            'method' => 'get',
            'action' => $action,
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ],
            ]
        ]); ?>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($rekapHarianForm, 'bulan',[
                    'addon' => ['prepend' => ['content'=>'Bulan']],
                ])->dropDownList(\app\components\Helper::getBulanList()) ?>

                <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

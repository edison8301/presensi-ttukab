<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 1/3/2019
 * Time: 10:09 AM
 */

use app\models\FilterForm;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$filter = new FilterForm();
?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Filter Tahun</h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'type' => 'inline',
            'method' => 'post',
            'action' => ['//filter'],
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
                <?= $form->field($filter, 'tahun',[
                    'addon' => ['prepend' => ['content'=>'Tahun']],
                ])->textInput(['maxlength' => true, 'type' => 'number']) ?>
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

use app\models\Pegawai;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $searchModel \app\modules\kinerja\models\InstansiPegawaiSkpSearch */

?>

<?php $form = ActiveForm::begin([
    'type' => 'inline',
    'action' => ['/' . Yii::$app->controller->route, 'id_pegawai' => $searchModel->id_pegawai],
    'method' => 'get',
]); ?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
    </div>

    <div class="box-body">

        <?= $form->field($searchModel, 'nomor')->widget(Select2::className(), [
            'data' => InstansiPegawaiSkp::getListNomor([
                'id_pegawai' => $searchModel->id_pegawai,
            ]),
            'options' => [
                'placeholder' => '- Pilih SKP -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '500px'
            ]
        ])->label(false); ?>

        <?= Html::submitButton('<i class="fa fa-search"></i> Filter', [
            'class' => 'btn btn-primary btn-flat'
        ]) ?>

    </div>

</div>

<?php ActiveForm::end() ?>

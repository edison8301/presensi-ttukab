<?php

/* @var $pegawaiExportForm \app\models\PegawaiExportForm */


\yii\bootstrap\Modal::begin([
    'header' => '<h3 class="modal-title">Pilih Data Pegawai Yang Akan Ditampilkan Pada Excel</h3>',
    'toggleButton' => ['label' => '<i class="fa fa-print"></i> Export Pegawai','class' => 'btn btn-primary btn-flat'],
    'size' => \yii\bootstrap\Modal::SIZE_LARGE
]); ?>

<?php $form = \kartik\form\ActiveForm::begin([
    'type' => \kartik\form\ActiveForm::TYPE_VERTICAL,
    'action' => Yii::$app->request->url.'&export=1',
    'method' => 'get',
]); ?>

<style>
    .checkboxgroup {
        overflow:auto;
    }
    .checkboxgroup div {
        width: 130px;
        float:left;
    }
</style>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-2">
            <label class="control-label">Pilih Instansi</label>
        </div>
        <div class="col-sm-5">
            <?= $form->field($pegawaiExportForm, 'id_instansi')->widget(\kartik\select2\Select2::class, [
                'data' => \app\models\Instansi::getList(),
                'options' => [
                    'placeholder' => '- Pilih Instansi -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ])->label(false) ?>
        </div>
    </div>

    <div class="row">
        <?= $form->field($pegawaiExportForm, 'kolom_export')->checkboxList(\app\models\PegawaiExportForm::getListKolom(),
            [
                'item'=>function ($index, $label, $name, $checked, $value){
                    return "<div class=\"col-sm-3\"><label style='font-weight: normal'><input type='checkbox' {$checked} name='{$name}' value='{$value}'> {$label}</label></div>";
                }
            ])->label(false) ?>
    </div>

    <?= $form->field($pegawaiExportForm, 'pilih_semua_kolom')->checkbox(['option' => 'value']); ?>
</div>

<div class="modal-footer">
    <?= \yii\helpers\Html::submitButton('<i class="fa fa-print"></i> Export Data Pegawai', ['class' => 'btn btn-primary btn-flat']) ?>
</div>

<?php \kartik\form\ActiveForm::end(); ?>

<?php \yii\bootstrap\Modal::end(); ?>


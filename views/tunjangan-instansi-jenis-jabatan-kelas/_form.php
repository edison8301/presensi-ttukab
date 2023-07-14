<?php

use app\models\Instansi;
use app\models\TunjanganInstansiJenisJabatanKelas;
use kartik\number\NumberControl;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganInstansiJenisJabatanKelas */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'layout'=>'horizontal',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="tunjangan-unit-jenis-jabatan-kelas-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Tunjangan Unit Jenis Jabatan Kelas</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'id_instansi')->widget(Select2::class, [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Unit -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'kategori')->dropDownList(TunjanganInstansiJenisJabatanKelas::findArrayKategori(),['prompt' => '- Pilih Kategori']) ?>

        <?= $form->field($model, 'id_jenis_jabatan')->dropDownList([
                1 => "Struktural",
                2 => "Fungsional",
                3 => "Pelaksana"
            ],['prompt' => '- Pilih Jenis Jabatan']) ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput() ?>

        <?php echo $form->field($model, 'nilai_tpp',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-3',
            ],
        ])->widget(NumberControl::class, [
            'maskedInputOptions' => [
                'groupSeparator' => '.',
                'radixPoint' => ',',
                'prefix' => '',
                'allowMinus' => false
            ],
        ]); ?>

        <?= $form->field($model, 'beban_kerja_persen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-1',
            ],
        ])->textInput([
            'type' => 'number',
            'min' => 0,
            'max' => 100
        ]) ?>

        <?= $form->field($model, 'prestasi_kerja_persen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-1',
            ],
        ])->textInput([
            'type' => 'number',
            'min' => 0,
            'max' => 100
        ]) ?>

        <?= $form->field($model, 'kondisi_kerja_persen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-1',
            ],
        ])->textInput([
            'type' => 'number',
            'min' => 0,
            'max' => 100
        ]) ?>

        <?= $form->field($model, 'tempat_bertugas_persen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-1',
            ],
        ])->textInput([
            'type' => 'number',
            'min' => 0,
            'max' => 100
        ]) ?>

        <?= $form->field($model, 'kelangkaan_profesi_persen',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-1',
            ],
        ])->textInput([
            'type' => 'number',
            'min' => 0,
            'max' => 100
        ]) ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Jabatan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer  */

$js = <<<JS
$('#jabatan-status_jumlah_tetap').change(function() {
  if($('#jabatan-status_jumlah_tetap').val() === '1') {
      $('.jumlah-tetap').css("display", 'block');
  } else {
      $('.jumlah-tetap').css("display", 'none');
  }
});
JS;
$this->registerJs($js, View::POS_READY, 'show-handler');
?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="jabatan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Jabatan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?php echo $form->field($model, 'id_induk')->widget(\kartik\select2\Select2::class, [
            'data' => \app\modules\tukin\models\Jabatan::getList($model->id_instansi, $model->id),
            'options' => [
                'placeholder' => '- Pilih Induk -',
            ],
        ]) ?>

        <?= $form->field($model, 'nama')->textArea(['rows' => 3]) ?>

        <?= $form->field($model, 'id_jenis_jabatan')->dropDownList(\app\modules\tukin\models\Jabatan::getJenisJabatanList()) ?>

        <?= $form->field($model, 'status_jumlah_tetap')->dropDownList([0 => 'Tidak Aktif', 1 => 'Aktif']); ?>

        <div class="jumlah-tetap" style="<?= $model->getIsJumlahTetap() ?: 'display : none' ?>">
            <?= $form->field($model, 'jumlah_tetap')->textInput(['maxlength' => true]) ?>
        </div>

        <?= $form->field($model, 'id_instansi')->widget(\kartik\select2\Select2::class, [
            'data' => \app\modules\tukin\models\Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
            ],
        ]) ?>

        <?= $form->field($model, 'bidang')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'subbidang')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput() ?>

        <?= $form->field($model, 'persediaan_pegawai')->textInput() ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

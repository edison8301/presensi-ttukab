<?php

use app\models\Pendidikan;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Instansi;
use app\models\Pegawai;
use app\models\PegawaiStatus;
use app\models\Golongan;
use app\models\Eselon;
use app\models\PegawaiJenis;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */


$js = <<<JS
$('#pegawai-status_absen_ceklis').change(function() {
  if($('#pegawai-status_absen_ceklis').val() === '1') {
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

<div class="pegawai-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Pegawai</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_pegawai_jenis')->dropDownList(
            PegawaiJenis::getList()
        ); ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
                'id' => 'id-instansi',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
        <?php } ?>

        <?= Html::hiddenInput('id-pegawai', $model->id, ['id' => 'id-pegawai']); ?>

        <?php /*
        <?= $form->field($model, 'id_jabatan')->widget(Select2::className(), [
            'data' => Jabatan::getList(),
            'options' => [
                'placeholder' => '- Pilih Status -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
        */ ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'id_jabatan')->widget(\kartik\select2\Select2::class, [
            'data' => \app\models\Jabatan::getList(),
            'options' => [
                'placeholder' => '- Pilih Jabatan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>
        <?php } ?>

        <?php if($model->isNewRecord) { ?>
            <?= $form->field($model, 'nama_jabatan')->textInput(['maxlength' => true]); ?>
        <?php } ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'id_golongan')->widget(Select2::className(), [
            //'data' => Pegawai::getList(),
            'data' => Golongan::getList(),
            'options' => [
                'placeholder' => '- Pilih Golongan -',
                'id' => 'id-golongan',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
        <?php } ?>

        <?php if($model->isNewRecord) { ?>
        <?= $form->field($model, 'id_eselon')->widget(Select2::className(), [
            //'data' => Pegawai::getList(),
            'data' => Eselon::getList(),
            'options' => [
                'placeholder' => '- Pilih Eselon -',
                'id' => 'id-eselon',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'params' => ['id-pegawai'],
                'loadingText' => 'Memuat pegawai'
            ]
        ]); ?>
        <?php } ?>

        <?php /*
        <?= $form->field($model, 'id_atasan')->widget(Select2::className(), [
            //'data' => Pegawai::getList(),
            'data' => $model->getListAtasan(),
            'options' => [
                'placeholder' => '- Pilih Atasan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
        */ ?>

        <?php /*
        <?= $form->field($model, 'id_atasan')->widget(DepDrop::className(), [
            'type' => DepDrop::TYPE_SELECT2,
            'data' => Pegawai::getListJson($model->id_eselon, $model->id_instansi, $model->id_golongan, true),
            'pluginOptions' => [
                'depends' => ['id-eselon', 'id-instansi', 'id-golongan'],
                'placeholder' => '- Pilih Atasan -',
                'url' => Url::to(['pegawai/get-list']),
            ]
        ]); ?>
        */ ?>

        <?= $form->field($model, 'gender')->widget(Select2::className(), [
            'data' => ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'],
            'options' => [
                'placeholder' => '- Pilih Gender -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>

        <?= $form->field($model, 'id_pendidikan')->widget(Select2::className(), [
            'data' => Pendidikan::getList(),
            'options' => [
                'placeholder' => '- Pilih Pendidikan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label('Pendidikan'); ?>

        <?= $form->field($model, 'tempat_lahir')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tanggal_lahir')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>

        <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'telepon')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'id_pegawai_status')->dropDownList(PegawaiStatus::getList()) ?>

        <?= $form->field($model, 'status_batas_pengajuan')->dropDownList([1 => 'Aktif', 0 => 'Tidak Aktif']) ?>

        <?= $form->field($model, 'status_absen_ceklis')->dropDownList([0 => 'Tidak Aktif', 1 => 'Aktif']) ?>

        <div class="jumlah-tetap" style="<?= $model->getIsAbsensiManual() ?: 'display : none' ?>">
            <?= $form->field($model, 'jenis_absen_ceklis')->dropDownList(Pegawai::getListShiftKerja(), ['prompt' => '- Pilih Jenis Shift Kerja -']) ?>
        </div>

        <?php /*
        <?= $form->field($model, 'foto')->textInput(['maxlength' => true]) ?>
        */ ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

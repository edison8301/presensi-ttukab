<?php

use app\components\Session;
use app\models\Eselon;
use app\models\InstansiBidang;
use app\models\Jabatan;
use app\models\JabatanEselon;
use app\models\JabatanEvjab;
use app\modules\tukin\models\Pegawai;
use app\modules\tunjangan\models\TingkatanFungsional;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
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

        <?= $form->errorSummary($model) ?>

        <?= $form->field($model, 'namaInstansi')->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Perangkat Daerah") ?>

        <?php if($model->accessIdInstansiBidang()) { ?>
        <?php echo $form->field($model, 'id_instansi_bidang')->dropDownList(
            InstansiBidang::getList(['id_instansi'=>$model->id_instansi]),
            ['prompt' => '- Pilih Bidang -']
        ) ?>
        <?php } ?>

        <?= $form->field($model, 'nama')->textArea(['rows' => 3]) ?>

        <?php /*
        <?= $form->field($model, 'nama_2021')->textArea(['rows' => 3]) ?>

        <?= $form->field($model, 'nama_2022')->textArea(['rows' => 3]) ?>

        <?= $form->field($model, 'nama_2023')->textArea(['rows' => 3]) ?>
        */ ?>

        <?= $form->field($model, 'nilai_jabatan')->textInput() ?>

        <?= $form->field($model, 'kelas_jabatan')->textInput() ?>

        <?= $form->field($model, 'id_jabatan_evjab')->widget(Select2::class, [
            'data' => JabatanEvjab::getList([
                'id_instansi' => $model->id_instansi,
                'id_instansi_bidang'=>$model->id_instansi_bidang
            ]),
            'options' => [
                'placeholder' => '- Pilih Jabatan Evjab -',
                'id' => 'id-instansi',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]) ?>

        <?php /* echo $form->field($model, 'id_jabatan_evjab')->dropDownList(
            JabatanEvjab::getList([
                'id_instansi' => $model->id_instansi,
                'id_instansi_bidang'=>$model->id_instansi_bidang
            ]),
            ['prompt' => '- Pilih Jabatan Evjab -']
        ) */ ?>

        <?php echo $form->field($model, 'id_induk')->widget(Select2::class, [
                'data' => Jabatan::getListStruktur($model->id_instansi, $model->status_kepala),
                'options' => ['prompt' => '- Pilih Induk -'],
                'pluginOptions' => ['allowClear' => true]
        ]) ?>

        <?= $form->field($model, 'id_jenis_jabatan')->dropDownList(\app\modules\tukin\models\Jabatan::getJenisJabatanList()) ?>

        <?= $form->field($model, 'id_eselon')->dropDownList(
                Eselon::getList(),
                ['prompt'=>'- Pilih Eselon -']
        )->label('Eselon (Struktural)'); ?>


        <?php /* $form->field($model, 'id_jabatan_eselon')->dropDownList(
                JabatanEselon::getList(),
                ['prompt'=>'- Pilih Eselon -']
        )->label('Eselon (Struktural)') */ ?>

        <?= $form->field($model, 'id_tingkatan_fungsional')->dropDownList(
                TingkatanFungsional::getList(),
                ['prompt'=>'- Pilih Tingkatan Fungsional -']
        )->label('Pilih Tingkatan (Fungsional)') ?>

        <?= $form->field($model, 'status_kepala',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->dropDownList(
            ['0'=>'Bukan','1'=>'Ya']
        )->label('Kepala Perangkat Daerah') ?>

        <?= $form->field($model, 'nilai_jabatan',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput(['rows' => 3]) ?>

        <?= $form->field($model, 'status_tampil',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-2',
                'error' => '',
                'hint' => '',
            ],
        ])->dropDownList(Jabatan::getListStatusTampil())->label('Status Tampil') ?>

        <?= Html::hiddenInput('referrer',$referrer) ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

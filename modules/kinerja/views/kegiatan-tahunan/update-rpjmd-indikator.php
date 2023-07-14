<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use app\models\Pegawai;
use app\models\InstansiPegawai;
use app\models\RpjmdKegiatanIndikator;
use app\models\RpjmdSasaranIndikator;
use app\models\RpjmdProgramIndikator;
use app\models\RpjmdSubkegiatanIndikator;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanTahunan */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

$butirKegiatanJf = 'butir_kegiatan_jf[0]';
$outputJf = 'output_jf[0]';
$angkaKreditJf = 'angka_kredit_jf[0]';

?>

<?php $form = ActiveForm::begin([
    //'type'=>'horizontal',
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

<div class="kegiatan-tahunan-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kinerja Tahunan</h3>
    </div>
	<div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'namaNipPegawai')->textInput(['readonly' => 'readonly']) ?>

        <?= $form->field($model, 'nomorSkpLengkap',[
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-4',
                'error' => '',
                'hint' => '',
            ]
        ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Nomor SKP") ?>

        <?php if($model->id_induk==null AND \app\components\Session::getTahun() < 2021) { ?>
            <?= $form->field($model, 'nomor_skp',[
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->dropDownList(InstansiPegawaiSkp::getListNomor()) ?>
        <?php } ?>

        <?php if($model->id_induk!=null AND \app\components\Session::getTahun() < 2021) { ?>
            <?= $form->field($model, 'nomorSkpLengkap',[
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Nomor SKP") ?>
        <?php } ?>

        <?php if($model->id_induk!=null) { ?>
            <?= $form->field($model, 'namaKegiatanInduk',[
                'horizontalCssClasses' => [
                    'label' => 'col-md-2',
                    'wrapper' => 'col-md-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->textarea(['rows'=>3,'readonly'=>'readonly'])->label("Kegiatan Induk") ?>
        <?php } ?>

        <?php if ($model->isVisibleRpjmdSasaranIndikator()) { ?>
        <div id="id-rpjmd-sasaran-indikator">
            <?= $form->field($model, 'id_rpjmd_sasaran_indikator')->widget(Select2::className(), [
                'data' => RpjmdSasaranIndikator::getList([
                    'id_instansi' => $model->getIdInstansiRpjmd()
                ]),
                'options' => [
                    'placeholder' => '- Pilih Indikator Sasaran Renstra -',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]); ?>
        </div>
        <?php } ?>

        <?php if ($model->isVisibleRpjmdProgramIndikator()) { ?>
            <div id="id-rpjmd-program-indikator">
                <?= $form->field($model, 'id_rpjmd_program_indikator')->widget(Select2::className(), [
                    'data' => RpjmdProgramIndikator::getList([
                        'id_instansi' => $model->getIdInstansiRpjmd()
                    ]),
                    'options' => [
                        'placeholder' => '- Pilih Indikator Program Renstra -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
        <?php } ?>

        <?php if ($model->isVisibleRpjmdKegiatanIndikator()) { ?>
            <div id="id-rpjmd-kegiatan-indikator">
                <?= $form->field($model, 'id_rpjmd_kegiatan_indikator')->widget(Select2::className(), [
                    'data' => RpjmdKegiatanIndikator::getList([
                        'id_instansi' => $model->getIdInstansiRpjmd()
                    ]),
                    'options' => [
                        'placeholder' => '- Pilih Indikator Kegiatan Renstra -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
        <?php } ?>

        <?php if ($model->isVisibleRpjmdSubKegiatanIndikator()) { ?>
            <div id="id-rpjmd-sub-kegiatan-indikator">
                <?= $form->field($model, 'id_rpjmd_subkegiatan_indikator')->widget(Select2::className(), [
                    'data' => RpjmdSubkegiatanIndikator::getList([
                        'id_instansi' => $model->getIdInstansiRpjmd()
                    ]),
                    'options' => [
                        'placeholder' => '- Pilih Indikator Sub Kegiatan Renstra -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
        <?php } ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

	</div>
    <div class="box-footer">
        <div class="col-sm-12">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>
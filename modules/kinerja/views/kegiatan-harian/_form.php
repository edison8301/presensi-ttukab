<?php

use app\modules\kinerja\models\KegiatanHarian;
use kartik\form\ActiveField;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use app\models\User;
use app\modules\kinerja\models\KegiatanHarianTambahan;
use app\modules\kinerja\models\KegiatanTahunan;
use app\models\InstansiPegawai;


/* @var $this yii\web\View */
/* @var $model KegiatanHarian */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

$tanggal = "window.location.href = location.origin + location.pathname + '?r=kinerja%2Fkegiatan-harian%2Fcreate&id_kegiatan_harian_jenis=$model->id_kegiatan_harian_jenis&tanggal=' + $(\"#" . Html::getInputId($model, 'tanggal') . "\").val()";

?>

<?php $form = ActiveForm::begin([
    'type'=>'horizontal',
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-md-2',
            'wrapper' => 'col-md-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="kegiatan-harian-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kegiatan Harian</h3>
    </div>
    <div class="box-body">

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'tanggal',[
            'hintType' => ActiveField::HINT_SPECIAL,
            'hintSettings' => [
                'iconBesideInput' => true,
                'onLabelClick' => false,
                'onLabelHover' => false,
                'onIconClick' => true,
                'onIconHover' => true,
                'title' => '<i class="glyphicon glyphicon-info-sign"></i> Keterangan'
            ]
        ])->widget(DatePicker::class, [
            'removeButton' => false,
            'options' => [
                'placeholder' => 'Tanggal',
                'onchange' => $tanggal,
            ],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->hint('Tanggal Kegiatan Dilakukan') ?>

        <?php if($model->getIsKegiatanSkp()==true) { ?>

            <?= $form->field($model, 'nomorSkpLengkap',[
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'iconBesideInput' => true,
                    'onLabelClick' => false,
                    'onLabelHover' => false,
                    'onIconClick' => true,
                    'onIconHover' => true,
                    'title' => '<i class="glyphicon glyphicon-info-sign"></i> Keterangan'
                ]
            ])->textArea(['rows'=>'3','readonly' => 'readonly'])->label('Nomor SKP')
            ->hint('Nomor SKP ditentukan secara otomatis dari kolom tanggal sesuai dengan tanggal berlaku SKP'); ?>

            <?= $form->field($model,'id_kegiatan_tahunan',[
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'iconBesideInput' => true,
                    'onLabelClick' => false,
                    'onLabelHover' => false,
                    'onIconClick' => true,
                    'onIconHover' => true,
                    'title' => '<i class="glyphicon glyphicon-info-sign"></i> Keterangan'
                ]
            ])->dropDownList(
                    KegiatanTahunan::getListBaru([
                        'id_instansi_pegawai'=>$model->getIdInstansiPegawai(),
                        'tanggal' => $model->tanggal,
                        'tahun'=>$model->getTahun(),
                        'hirarki'=>true,
                        'id_kegiatan_status'=>1,
                        'id_kegiatan_tahunan_versi' => 1,
                    ]),
                    ['prompt'=>'- Pilih Kegiatan SKP -']
            )->hint('Hanya menampilkan daftar kegiatan SKP yang sudah disetujui'); ?>
        <?php } ?>

        <?php if($model->getIsKegiatanSkp()==false) { ?>
            <?= $form->field($model,'id_kegiatan_harian_tambahan',[
                'hintType' => ActiveField::HINT_SPECIAL,
                'hintSettings' => [
                    'iconBesideInput' => true,
                    'onLabelClick' => false,
                    'onLabelHover' => false,
                    'onIconClick' => true,
                    'onIconHover' => true,
                    'title' => '<i class="glyphicon glyphicon-info-sign"></i> Keterangan'
                ]
            ])->dropDownList(
                KegiatanHarianTambahan::getList(),
                ['prompt'=>'- Pilih Kegiatan Tambahan -']
            )->hint('Diisi dengan memilih jenis kegiatan tambahan (non-SKP) yang dilakukan'); ?>
        <?php } ?>

        <?= $form->field($model, 'uraian',[
            'hintType' => ActiveField::HINT_SPECIAL,
            'hintSettings' => [
                'iconBesideInput' => true,
                'onLabelClick' => false,
                'onLabelHover' => false,
                'onIconClick' => true,
                'onIconHover' => true,
                'title' => '<i class="glyphicon glyphicon-info-sign"></i> Keterangan'
            ]
        ])->textArea(['rows' => 4])->hint('Diisi dengan rincian/uraian detail dari kegiatan yang sudah dikerjakan') ?>

        <?= $form->field($model, 'kuantitas',[
            'horizontalCssClasses' => [
                'label' => 'col-md-2',
                'wrapper' => 'col-md-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput() ?>

        <?= $form->field($model, 'satuan',[
            'horizontalCssClasses' => [
                'label' => 'col-md-2',
                'wrapper' => 'col-md-2',
                'error' => '',
                'hint' => '',
            ],
        ])->textInput() ?>

        <?= $form->field($model, 'jam_mulai', [
            'horizontalCssClasses' => [
                'label' => 'col-md-2',
                'wrapper' => 'col-md-2',
                'error' => '',
                'hint' => '',
            ],
        ])->widget(TimePicker::class, [
            'pluginOptions' => [
                'showMeridian' => false,
            ]
        ]); ?>

        <?= $form->field($model, 'jam_selesai', [
            'horizontalCssClasses' => [
                'label' => 'col-md-2',
                'wrapper' => 'col-md-2',
                'error' => '',
                'hint' => '',
            ],
        ])->widget(TimePicker::class, [
            'pluginOptions' => [
                'showMeridian' => false,
            ]
        ]); ?>

        <?= Html::hiddenInput('referrer',$referrer); ?>

    </div>
    <div class="box-footer">
        <div class="col-sm-offset-2 col-sm-3">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

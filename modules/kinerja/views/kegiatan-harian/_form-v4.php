<?php

use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanHarianTambahan;
use kartik\date\DatePicker;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use kartik\time\TimePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\kinerja\models\KegiatanHarian */
/* @var $referrer string */

?>

<?php $form = ActiveForm::begin([
    //'type'=>'horizontal',
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

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Form Kinerja Harian</h3>
    </div>

    <div class="box-body">

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
                'disabled' => true,
            ],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ])->hint('Tanggal Kegiatan Dilakukan') ?>

        <?php if ($model->id_kegiatan_harian_jenis == KegiatanHarianJenis::UTAMA) { ?>
            <?= $form->field($model, 'nomorSkpLengkap', [
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
        <?php } ?>

        <?php if($model->id_kegiatan_harian_jenis == KegiatanHarianJenis::TAMBAHAN) { ?>
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

        <?= $form->field($model,'id_kegiatan_tahunan', [
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
            $model->getListKegiatanTahunan([
                'id_kegiatan_tahunan_versi' => 3,
                'target_is_not_null' => false,
            ]),
            ['prompt' => '- Indikator Kinerja Individu -']
        )
        ->hint('Hanya menampilkan daftar kegiatan SKP yang sudah disetujui')
        ->label("Indikator Kinerja Individu"); ?>

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
        ])->textArea(['rows' => 4, 'minLength' => 20])->hint('Diisi dengan rincian/uraian detail dari kegiatan yang sudah dikerjakan') ?>

        <?= $form->field($model, 'realisasi')->textInput()->label('Realisasi'); ?>

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
        <div class="col-sm-12">
            <?= Html::submitButton('<i class="fa fa-check"></i> Simpan',['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('<i class="fa fa-remove"></i> Batal',Yii::$app->request->referrer,['class' => 'btn btn-warning btn-flat']) ?>
        </div>
    </div>

</div>

<?php ActiveForm::end(); ?>

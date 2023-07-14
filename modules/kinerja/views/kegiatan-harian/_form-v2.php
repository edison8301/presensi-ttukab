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

$tanggal = "window.location.href = location.origin + location.pathname + '?r=kinerja%2Fkegiatan-harian%2Fcreate-v2&id_kegiatan_harian_jenis=$model->id_kegiatan_harian_jenis&id_kegiatan_tahunan=$model->id_kegiatan_tahunan&tanggal=' + $(\"#" . Html::getInputId($model, 'tanggal') . "\").val()";

$bulan = date('n');

$datetime = DateTime::createFromFormat('Y-m-d',$model->tanggal);

if($datetime != false) {
    $bulan = $datetime->format('n');
}
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

<div class="kegiatan-harian-form box box-primary">

    <div class="box-header">
        <h3 class="box-title">Form Kinerja Harian</h3>
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
                    $model->getListKegiatanTahunan(['id_kegiatan_tahunan_versi' => 2]),
                    ['prompt'=>'- Pilih Rencana Kinerja -', 'disabled'=> true]
            )->hint('Hanya menampilkan daftar kegiatan SKP yang sudah disetujui')->label("Rencana Kinerja"); ?>
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

        <div class="form-group">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center">Aspek</th>
                    <th style="text-align: center">Indikator Kinerja Individu</th>
                    <th style="text-align: center">Target Bulan</th>
                    <th style="text-align: center">Realisasi</th>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">Kuantitas</td>
                    <td><?= @$model->kegiatanTahunan->indikator_kuantitas; ?></td>
                    <td style="text-align: center">
                        <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target; ?>
                        <?= @$model->kegiatanTahunan->satuan_kuantitas; ?>
                    </td>
                    <td>
                        <?= $form->field($model, 'realisasi_kuantitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-2',
                                'wrapper' => 'col-md-2',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textInput() ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">Kualitas</td>
                    <td><?= @$model->kegiatanTahunan->indikator_kualitas; ?></td>
                    <td style="text-align: center">
                        <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_kualitas; ?>
                        <?= @$model->kegiatanTahunan->satuan_kualitas; ?>
                    </td>
                    <td>
                        <?= $form->field($model, 'realisasi_kualitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-2',
                                'wrapper' => 'col-md-2',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textInput() ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">Waktu</td>
                    <td><?= @$model->kegiatanTahunan->indikator_waktu; ?></td>
                    <td style="text-align: center">
                        <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_waktu; ?>
                        <?= @$model->kegiatanTahunan->satuan_waktu; ?>
                    </td>
                    <td>
                        <?= $form->field($model, 'realisasi_waktu',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-2',
                                'wrapper' => 'col-md-2',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textInput() ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">Biaya</td>
                    <td><?= @$model->kegiatanTahunan->indikator_biaya; ?></td>
                    <td style="text-align: center">
                        <?= $model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_biaya; ?>
                        <?= @$model->kegiatanTahunan->satuan_biaya; ?>
                    </td>
                    <td>
                        <?= $form->field($model, 'realisasi_biaya',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-2',
                                'wrapper' => 'col-md-2',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textInput() ?>
                    </td>
                </tr>
            </table>
        </div>

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

        <?php /*
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
        */ ?>

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

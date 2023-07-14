<?php

use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianJenis;
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
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model KegiatanHarian */
/* @var $form yii\widgets\ActiveForm */
/* @var $referrer string */

$tanggal = "window.location.href = location.origin + location.pathname + '?r=kinerja%2Fkegiatan-harian%2Fcreate-v3&id_kegiatan_harian_jenis=$model->id_kegiatan_harian_jenis&id_kegiatan_tahunan=$model->id_kegiatan_tahunan&tanggal=' + $(\"#" . Html::getInputId($model, 'tanggal') . "\").val()";

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
            $model->getListKegiatanTahunan(['id_kegiatan_tahunan_versi' => 2, 'target_is_not_null' => false]),
            ['prompt' => '- Pilih Rencana Kinerja -']
        )->hint('Hanya menampilkan daftar kegiatan SKP yang sudah disetujui')->label("Rencana Kinerja"); ?>

        <div class="form-group">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center; width: 100px;">Aspek</th>
                    <th style="text-align: center">Indikator Kinerja Individu</th>
                    <th style="text-align: center; width: 200px">Target Bulan</th>
                    <th style="text-align: center; width: 300px;">Realisasi</th>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">Kuantitas</td>
                    <td id="indikator-kuantitas">
                        <?= @$model->kegiatanTahunan->indikator_kuantitas; ?>
                    </td>
                    <td id="target-kuantitas" style="text-align: center">
                        <?php if (@$model->kegiatanTahunan != null) { ?>
                            <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target; ?>
                            <?= @$model->kegiatanTahunan->satuan_kuantitas; ?>
                        <?php } ?>
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
                    <td id="indikator-kualitas">
                        <?= @$model->kegiatanTahunan->indikator_kualitas; ?>
                    </td>
                    <td id="target-kualitas" style="text-align: center">
                        <?php if (@$model->kegiatanTahunan != null) { ?>
                            <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_kualitas; ?>
                            <?= @$model->kegiatanTahunan->satuan_kualitas; ?>
                        <?php } ?>
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
                    <td id="indikator-waktu">
                        <?= @$model->kegiatanTahunan->indikator_waktu; ?>
                    </td>
                    <td id="target-waktu" style="text-align: center">
                        <?php if (@$model->kegiatanTahunan != null) { ?>
                            <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_waktu; ?>
                            <?= @$model->kegiatanTahunan->satuan_waktu; ?>
                        <?php } ?>
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
                    <td id="indikator-biaya">
                        <?= @$model->kegiatanTahunan->indikator_biaya; ?>
                    </td>
                    <td id="target-biaya" style="text-align: center">
                        <?php if (@$model->kegiatanTahunan != null) { ?>
                            <?= @$model->kegiatanTahunan->findKegiatanBulananByBulan($bulan)->target_biaya; ?>
                            <?= @$model->kegiatanTahunan->satuan_biaya; ?>
                        <?php } ?>
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
        ])->textArea(['rows' => 4, 'minLength' => 20])->hint('Diisi dengan rincian/uraian detail dari kegiatan yang sudah dikerjakan') ?>

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

<?php $this->registerJs(<<<JS
    $(document).ready(function() {

        let kegiatanTahunan = $('#kegiatanharian-id_kegiatan_tahunan');

        let indikatorKuantitas = $('#indikator-kuantitas');
        let indikatorKualitas = $('#indikator-kualitas');
        let indikatorWaktu = $('#indikator-waktu');
        let indikatorBiaya = $('#indikator-biaya');

        let targetKuantitas = $('#target-kuantitas');
        let targetKualitas = $('#target-kualitas');
        let targetWaktu = $('#target-waktu');
        let targetBiaya = $('#target-biaya');

        function clear() {
            indikatorKuantitas.html('');
            indikatorKualitas.html('');
            indikatorWaktu.html('');
            indikatorBiaya.html('');

            targetKuantitas.html('');
            targetKualitas.html('');
            targetWaktu.html('');
            targetBiaya.html('');
        }

        kegiatanTahunan.on('change', function() {

            let id = $(this).val();
            let url = 'index.php?r=api2/kegiatan-tahunan/view&id=' + $(this).val() + '&bulan=' + $bulan;

            if (id != null && id != '') {
                $.get(url, function(data) {
                    indikatorKuantitas.html(data.indikator_kuantitas);
                    indikatorKualitas.html(data.indikator_kualitas);
                    indikatorWaktu.html(data.indikator_waktu);
                    indikatorBiaya.html(data.indikator_biaya);

                    targetKuantitas.html(data.target_kuantitas_bulan + ' ' + data.satuan_kuantitas);
                    targetKualitas.html(data.target_kualitas_bulan + ' ' + data.satuan_kualitas);
                    targetWaktu.html(data.target_waktu_bulan + ' ' + data.satuan_waktu);
                    targetBiaya.html(data.target_biaya_bulan + ' ' + data.satuan_biaya);
                }).fail(function() {
                    clear();
                });
            } else {
                clear();
            }
        })

    });
JS
, View::POS_READY, 'show-handler'); ?>

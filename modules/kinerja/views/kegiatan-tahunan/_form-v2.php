<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
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

        <?= $form->field($model, 'id_kegiatan_tahunan_jenis')->dropDownList(
            ['1' => 'Kinerja Utama','2'=>'Kinerja Tambahan'],
            ['id' => 'id-kegiatan-tahunan-jenis']
        )->label('Jenis Kinerja'); ?>

        <?php if($model->isVisibleIdKegiatanTahunanAtasan() == true) { ?>
        <div id="id-kegiatan-tahunan-atasan">
            <?php print $form->field($model, 'id_kegiatan_tahunan_atasan')->dropDownList(
                $model->getListKegiatanTahunanAtasan(['id_kegiatan_tahunan_versi' => 2]),
                ['prompt' => 'Pilih Kinerja Tahunan Atasan']
            )->label('Rencana Kerja Atasan Langsung yang Didukung'); ?>
        </div>
        <?php } ?>

        <?= $this->render('_input-indikator-renstra', [
            'form' => $form,
            'model' => $model,
        ]) ?>

        <?= $form->field($model, 'nama')->textArea(['rows' => 5])->label('Rencana Kinerja') ?>

        <div class="form-group">
            <table class="table">
                <tr>
                    <th style="text-align: center; width: 100px">Aspek</th>
                    <th style="text-align: center">Indikator Kinerja Individu</th>
                    <th style="text-align: center; width: 25%">Target</th>
                    <th style="text-align: center; width: 25%">Satuan Target</th>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        Kuantitas <span style="color: #ff0000">*</span>
                    </td>
                    <td>
                        <?= $form->field($model, 'indikator_kuantitas',[
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'template' => "{input}",
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Indikator Kuantitas'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'target_kuantitas', [
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'template' => "{input}",
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Target Kuantitas (Angka)'])->label(false)->hint(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'satuan_kuantitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Satuan Kuantitas'])->label(false) ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        Kualitas
                    </td>
                    <td>
                        <?= $form->field($model, 'indikator_kualitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Indikator Kualitas'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'target_kualitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Target Kualitas'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'satuan_kualitas',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Satuan Kualitas'])->label(false) ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        Waktu
                    </td>
                    <td>
                        <?= $form->field($model, 'indikator_waktu',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Indikator Waktu'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'target_waktu',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Target Waktu'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'satuan_waktu',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Satuan Waktu'])->label(false) ?>
                    </td>
                </tr>
                <?php //if ($model->pegawai !== null && $model->pegawai->getIsEselonII()) { ?>
                <tr>
                    <td style="text-align: center; font-weight: bold">
                        Biaya
                    </td>
                    <td>
                        <?= $form->field($model, 'indikator_biaya',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Indikator Biaya'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'target_biaya',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Target Biaya'])->label(false) ?>
                    </td>
                    <td style="text-align: center">
                        <?= $form->field($model, 'satuan_biaya',[
                            'template' => "{input}",
                            'options' => [
                                'style' => 'margin-bottom:0px'
                            ],
                            'horizontalCssClasses' => [
                                'label' => 'col-md-12',
                                'wrapper' => 'col-md-12',
                                'error' => '',
                                'hint' => '',
                            ],
                        ])->textarea(['rows' => 3,'placeholder'=>'Satuan Biaya'])->label(false) ?>
                    </td>
                </tr>
                <?php //} ?>
            </table>
        </div>

        <table class="table table-fungsional">
            <thead>
                <tr>
                    <th style="text-align:center;width:50%;">Butir Kegiatan (Khusus JF)</th>
                    <th style="text-align:center;">Output (Khusus JF)</th>
                    <th style="text-align:center;">Angka Kredit (Khusus JF)</th>
                    <th></th>
                </tr>
            </thead>
            <tr class="line">
                <td>
                    <?= $form->field($model, $butirKegiatanJf,[
                        //'template' => "{input}",
                        'options' => [
                            'style' => 'margin-bottom:0px'
                        ],
                        'horizontalCssClasses' => [
                            'label' => 'col-md-12',
                            'wrapper' => 'col-md-12',
                            'error' => '',
                            'hint' => '',
                        ],
                    ])->textarea(['rows' => 3, 'placeholder' => 'Butir Kegiatan'])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($model, $outputJf,[
                        //'template' => "{input}",
                        'options' => [
                            'style' => 'margin-bottom:0px'
                        ],
                        'horizontalCssClasses' => [
                            'label' => 'col-md-12',
                            'wrapper' => 'col-md-12',
                            'error' => '',
                            'hint' => '',
                        ],
                    ])->textInput(['placeholder' => 'Output', 'autocomplete' => 'off'])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($model, $angkaKreditJf,[
                        //'template' => "{input}",
                        'options' => [
                            'style' => 'margin-bottom:0px',
                        ],
                        'horizontalCssClasses' => [
                            'label' => 'col-md-12',
                            'wrapper' => 'col-md-12',
                            'error' => '',
                            'hint' => '',
                        ],
                    ])->textInput([
                        'placeholder' => 'Angka Kredit',
                        'autocomplete' => 'off',
                    ])->label(false) ?>
                </td>
                <td class="add" style="text-align: center">
                    <?= Html::a('<i class="fa fa-plus"></i>', 'javascript:void(0)', ['class' => 'btn btn-success btn-flat btn-add']) ?>
                </td>
            </tr>
            <!-- Untuk generate row ketika update data -->
            <?php if (!$model->isNewRecord) {
                echo $this->render('_tr-input-fungsional', [
                    'form' => $form,
                    'model' => $model,
                ]);
            } ?>
        </table>

        <?php /*
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'indikator_kuantitas',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'target_kuantitas',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'indikator_kualitas',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'target_kualitas',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'indikator_waktu',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'target_kuantitas',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'indikator_waktu',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'target_waktu',[
                    'horizontalCssClasses' => [
                        'label' => 'col-md-6',
                        'wrapper' => 'col-md-6',
                        'error' => '',
                        'hint' => '',
                    ],
                ])->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        */ ?>

        <?php if (User::isAdmin()) { ?>
        <?= $form->field($model, 'id_kegiatan_status')->widget(Select2::className(), [
            'data' => KegiatanStatus::getList(),
            'options' => [
                'placeholder' => '- Pilih Status -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ]); ?>
        <?php }; ?>

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

<?php
    $jumlah = count($model->manyKegiatanTahunanFungsional);
    if($jumlah == 0) {
        $jumlah = 1;
    }
?>

<?php $this->registerJs(<<<JS
  $(document).ready(function() {
    isNewRecord = "$model->isNewRecord";

    i = 1;

    if(isNewRecord == false) {
        i = "$jumlah";
    }

    $('.btn-add').on('click', function() {
        let tr = $(this).closest('tr');
        let clone = tr.clone();
        clone.find('.add').html(`
            <a href='javascript:void(0)' class="btn btn-danger btn-flat btn-remove">
                <i class="fa fa-remove"></i>
            </a>`)
        clone.find(':input').val('');

        clone.find('#kegiatantahunan-butir_kegiatan_jf-0').attr('name', 'KegiatanTahunan[butir_kegiatan_jf]['+(i)+']');
        clone.find('#kegiatantahunan-output_jf-0').attr('name', 'KegiatanTahunan[output_jf]['+(i)+']');
        clone.find('#kegiatantahunan-angka_kredit_jf-0').attr('name', 'KegiatanTahunan[angka_kredit_jf]['+(i)+']');
        i++;

        $('.table-fungsional tr:last').after(clone).add(
            remove()
        );
    });

    remove();

    function remove() {
        $('.btn-remove').on('click', function() {
            console.log('remove()');
            $(this).closest('tr').remove();
        });
    }

    // -- //
    let nilai = $('#id-kegiatan-tahunan-jenis').val();
    if(nilai != '1') {
        $('#id-kegiatan-tahunan-atasan').hide();
        $('#id-rpjmd-sasaran-indikator').hide();
        $('#id-rpjmd-program-indikator').hide();
        $('#id-rpjmd-kegiatan-indikator').hide();
        $('#id-rpjmd-sub-kegiatan-indikator').hide();
        $('#id-rpjmd-indikator-fungsional').hide();
    }

    $('#id-kegiatan-tahunan-jenis').on('change', function() {
        let nilai = $(this).val();
        if(nilai == '1') {
            $('#id-kegiatan-tahunan-atasan').show('slow');
            $('#id-rpjmd-sasaran-indikator').show('slow');
            $('#id-rpjmd-program-indikator').show('slow');
            $('#id-rpjmd-kegiatan-indikator').show('slow');
            $('#id-rpjmd-sub-kegiatan-indikator').show('slow');
            $('#id-rpjmd-indikator-fungsional').show('slow');
        } else {
            $('#id-kegiatan-tahunan-atasan').hide('slow');
            $('#id-rpjmd-sasaran-indikator').hide('slow');
            $('#id-rpjmd-program-indikator').hide('slow');
            $('#id-rpjmd-kegiatan-indikator').hide('slow');
            $('#id-rpjmd-sub-kegiatan-indikator').hide('slow');
            $('#id-rpjmd-indikator-fungsional').hide('slow');
        }
    });
  });
JS
, View::POS_READY, 'show-handler'); ?>

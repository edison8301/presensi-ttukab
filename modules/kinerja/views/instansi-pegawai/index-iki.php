<?php

use app\models\InstansiPegawai;
use app\models\User;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model \app\modules\kinerja\models\IkiForm */

$this->title = 'IKI Pegawai';
?>

<?php $form = ActiveForm::begin([
    'action' => ['instansi-pegawai/index-iki'],
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter IKI</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'id_pegawai')->widget(Select2::class, [
                    'data' => $model->getListPegawai(),
                    'options' => [
                        'placeholder' => '- Pilih Pegawai -',
                        'id' => 'id-pegawai',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'id_instansi_pegawai')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'data' => InstansiPegawai::getListInstansi($model->id_pegawai, true),
                    'pluginOptions' => [
                        'depends' => ['id-pegawai'],
                        'placeholder' => '- Pilih Jabatan -',
                        'url' => Url::to(['/instansi-pegawai/get-list']),
                    ],
                    'select2Options' => [
                        'pluginOptions' => [
                            'allowClear' => true,
                        ]
                    ]
                ])->label(false) ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>

<?php if ($model->isFiltered()) { ?>
    <div class="box box-primary">
        <div class="box-header with-border text-center">
            <h3 class="box-title" style="text-transform: uppercase">Indikator Kinerja Individu</h3>
        </div>
        <div class="box-header">
            <?php if (User::isPegawai()) { ?>
                <?= Html::a('<i class="fa fa-plus"></i> Tambah Tugas', ['instansi-pegawai-tugas/create', 'id_instansi_pegawai' => $model->id_instansi_pegawai], ['class' => 'btn btn-success btn-flat']) ?>
                <?= Html::a('<i class="fa fa-plus"></i> Tambah Fungsi', ['instansi-pegawai-fungsi/create', 'id_instansi_pegawai' => $model->id_instansi_pegawai], ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>
            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF', ['instansi-pegawai/export-pdf-iki', 'id' => $model->id_instansi_pegawai], ['class' => 'btn btn-danger btn-flat', 'target' => '_blank']) ?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', ['instansi-pegawai/export-excel-iki', 'id' => $model->id_instansi_pegawai], ['class' => 'btn btn-success btn-flat', 'target' => '_blank']) ?>
        </div>
        <div class="box-body">
            <table class="table table-condensed">
                <tr>
                    <th style="width: 30px">1.</th>
                    <th>Jabatan</th>
                    <td>:</td>
                    <td>
                        <?= $model->instansiPegawai->getNamaJabatan(false) ?>
                    </td>
                </tr>
                <tr>
                    <th style="width: 30px">2.</th>
                    <th>Tugas</th>
                    <td>:</td>
                    <td>
                        <ol style="list-style-type: lower-alpha;padding-inline-start: 20px;">
                            <?php foreach ($model->instansiPegawai->manyInstansiPegawaiTugas as $tugas) { ?>
                                <li style="margin-bottom: 10px">
                                    <?php if (User::isPegawai()) {
                                        echo ButtonDropdown::widget([
                                            'label' => '',
                                            'options' => [
                                                'class' => 'btn btn-primary btn-xs btn-flat'
                                            ],
                                            'dropdown' => [
                                                'encodeLabels' => false,
                                                'items' => [
                                                    ['label' => '<i class="fa fa-pencil"></i>Sunting', 'url' => ['instansi-pegawai-tugas/update', 'id' => $tugas->id]],
                                                    [
                                                        'label' => '<i class="fa fa-trash"></i>Hapus',
                                                        'url' => ['instansi-pegawai-tugas/delete', 'id' => $tugas->id],
                                                        'linkOptions' => [
                                                            'data-confirm' => 'Yakin akan menghapus Tugas?',
                                                            'data-method' => 'post'
                                                        ],
                                                    ],
                                                    // '<li class="divider"></li>',
                                                    // ['label' => '<i class="fa fa-arrow-up"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                                    // ['label' => '<i class="fa fa-arrow-down"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                                ],
                                            ],
                                        ]);
                                    } ?>
                                    <?= $tugas->nama ?>
                                </li>
                            <?php } ?>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <th style="width: 30px">3.</th>
                    <th>Fungsi</th>
                    <td>:</td>
                    <td>
                        <ol style="list-style-type: lower-alpha;padding-inline-start: 20px;">
                            <?php foreach ($model->instansiPegawai->manyInstansiPegawaiFungsi as $fungsi) { ?>
                                <li style="margin-bottom: 10px">
                                    <?php if (User::isPegawai()) {
                                        echo ButtonDropdown::widget([

                                            'label' => '',
                                            'options' => [
                                                'class' => 'btn btn-primary btn-xs btn-flat'
                                            ],
                                            'dropdown' => [
                                                'encodeLabels' => false,
                                                'items' => [
                                                    ['label' => '<i class="fa fa-pencil"></i>Sunting', 'url' => ['instansi-pegawai-fungsi/update', 'id' => $fungsi->id]],
                                                    [
                                                        'label' => '<i class="fa fa-trash"></i>Hapus',
                                                        'url' => ['instansi-pegawai-fungsi/delete', 'id' => $fungsi->id],
                                                        'linkOptions' => [
                                                            'data-confirm' => 'Yakin akan menghapus Fungsi?',
                                                            'data-method' => 'post'
                                                        ],
                                                    ],
                                                    // '<li class="divider"></li>',
                                                    // ['label' => '<i class="fa fa-arrow-up"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                                    // ['label' => '<i class="fa fa-arrow-down"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                                ],
                                            ],
                                        ]);
                                    } ?>
                                    <?= $fungsi->nama ?>
                                </li>
                            <?php } ?>
                        </ol>
                    </td>
                </tr>
            </table>
            <br>
            <?php if (User::isPegawai()) { ?>
                <?= Html::a('<i class="fa fa-plus"></i> Tambah Sasaran', ['instansi-pegawai-sasaran/create', 'id_instansi_pegawai' => $model->id_instansi_pegawai], ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>
            <br>
            <br>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center; width: 30px">No.</th>
                    <th style="text-align: center">Sasaran</th>
                    <th style="text-align: center">Indikator Kinerja</th>
                    <th style="text-align: center">Penjelasan/Formulasi Perhitungan</th>
                    <th style="text-align: center">Sumber Data</th>
                </tr>
                <tr>
                    <th style="text-align: center">(1)</th>
                    <th style="text-align: center">(2)</th>
                    <th style="text-align: center">(3)</th>
                    <th style="text-align: center">(4)</th>
                    <th style="text-align: center">(5)</th>
                </tr>
                </thead>
                <?php $i = 1; ?>
                <?php foreach ($model->instansiPegawai->manyInstansiPegawaiSasaran as $sasaran) { ?>
                    <?php $manyIndikator = $sasaran->manyInstansiPegawaiSasaranIndikator ?>
                    <?php $countIndikator = count($manyIndikator) ?>
                    <?php $firstIndikator = $manyIndikator !== [] ? array_shift($manyIndikator) : null ?>
                    <tr>
                        <td rowspan="<?= $countIndikator ?>"><?= $i++ ?></td>
                        <td rowspan="<?= $countIndikator ?>">
                            <?php if (User::isPegawai()) {
                                echo ButtonDropdown::widget([
                                    'label' => '',
                                    'options' => [
                                        'class' => 'btn btn-primary btn-xs btn-flat'
                                    ],
                                    'dropdown' => [
                                        'encodeLabels' => false,
                                        'items' => [
                                            ['label' => '<i class="fa fa-plus"></i> Tambah Indikator Kinerja', 'url' => ['instansi-pegawai-sasaran-indikator/create', 'id_instansi_pegawai_sasaran' => $sasaran->id]],
                                            '<li class="divider"></li>',
                                            ['label' => '<i class="fa fa-pencil"></i>Sunting', 'url' => ['instansi-pegawai-sasaran/update', 'id' => $sasaran->id]],
                                            [
                                                'label' => '<i class="fa fa-trash"></i>Hapus',
                                                'url' => ['instansi-pegawai-sasaran/delete', 'id' => $sasaran->id],
                                                'linkOptions' => [
                                                    'data-confirm' => 'Yakin akan menghapus Sasaran?',
                                                    'data-method' => 'post'
                                                ],
                                            ],
                                            // '<li class="divider"></li>',
                                            // ['label' => '<i class="fa fa-arrow-up"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                            // ['label' => '<i class="fa fa-arrow-down"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                                        ],
                                    ],
                                ]);
                            } ?>
                            <?= $sasaran->nama ?>
                        </td>
                        <?php if ($firstIndikator !== null) { ?>
                            <?= $this->render('_td-indikator', ['indikator' => $firstIndikator, 'tr' => false]) ?>
                        <?php } else { ?>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php } ?>
                    </tr>
                    <?php foreach ($manyIndikator as $indikator) { ?>
                        <?= $this->render('_td-indikator', ['indikator' => $indikator, 'tr' => true]) ?>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } ?>

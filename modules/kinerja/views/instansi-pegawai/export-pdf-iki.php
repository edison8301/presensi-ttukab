<?php

use app\models\User;
use yii\bootstrap\ButtonDropdown;

/* @var $this \yii\web\View */
/* @var $model \app\models\InstansiPegawai */
?>

<div class="box box-primary">
    <div class="box-header with-border text-center">
        <h4 class="box-title" style="text-transform: uppercase">Indikator Kinerja Individu</h4>
    </div>
    <div class="box-body">
        <br>
        <table class="table table-condensed">
            <tr>
                <th style="width: 30px">1.</th>
                <th style="width: 150px">Jabatan</th>
                <td style="width: 20px">:</td>
                <td>
                    <?= $model->getNamaJabatan(false) ?>
                </td>
            </tr>
            <tr>
                <th style="width: 30px">2.</th>
                <th>Tugas</th>
                <td>:</td>
                <td>
                    <?php $chr = 97 ?>
                    <?php foreach ($model->manyInstansiPegawaiTugas as $tugas) { ?>
                        <?= chr($chr++) . '. ' . $tugas->nama ?><br>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th style="width: 30px">3.</th>
                <th>Fungsi</th>
                <td>:</td>
                <td>
                    <?php $chr = 97 ?>
                    <?php foreach ($model->manyInstansiPegawaiFungsi as $fungsi) { ?>
                        <?= chr($chr++) . '. ' . $fungsi->nama ?><br>
                    <?php } ?>
                </td>
            </tr>
        </table>
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
            <?php foreach ($model->manyInstansiPegawaiSasaran as $sasaran) { ?>
                <?php $manyIndikator = $sasaran->manyInstansiPegawaiSasaranIndikator ?>
                <?php $countIndikator = count($manyIndikator) ?>
                <?php $firstIndikator = $manyIndikator !== [] ? array_shift($manyIndikator) : null ?>
                <tr>
                    <td rowspan="<?= $countIndikator ?>" style="text-align: center"><?= $i++ ?></td>
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
                                            'url' => ['instansi-jabatan-sasaran/delete', 'id' => $sasaran->id],
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

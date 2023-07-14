<?php


/* @var $this \yii\web\View */
/* @var $searchModel \app\models\InstansiPegawaiSearch */

/* @var $dataProvider \yii\data\ActiveDataProvider */

/* @var $querySearch \yii\data\ActiveDataProvider */

use app\components\Helper;
use app\models\Instansi;
use app\models\InstansiPegawai;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Rekap IKI';
?>

<?php $form = ActiveForm::begin([
    'action' => ['instansi-pegawai/rekap-iki'],
    'layout' => 'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Rekap IKI</h3>
    </div>
    <div class="box-body">
        <?= $form->field($searchModel, 'bulan')->widget(Select2::className(), [
            'data' => Helper::getListBulan(),
            'options' => [
                'placeholder' => '- Pilih Bulan -',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '300px'
            ]
        ]); ?>

        <?= $form->field($searchModel, 'id_instansi')->widget(Select2::className(), [
            'data' => Instansi::getList(),
            'options' => [
                'placeholder' => '- Pilih Instansi -',
                'id' => 'id-eselon',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '450px'
            ]
        ]); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php if ($searchModel->isFiltered()) { ?>
    <div class="box box-primary">
        <div class="box-header">
            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF Rekap', Url::current(['export' => 1]), ['target' => '_blank', 'class' => 'btn btn-danger btn-flat']) ?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel Rekap', Url::current(['exportExcel' => 1]), ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="box-body" style="overflow: auto;">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Nama</th>
                    <th style="text-align: center">Gol</th>
                    <th style="text-align: center">Eselon</th>
                    <th style="text-align: center">Jabatan</th>
                    <th style="text-align: center">Jenis Jabatan</th>
                    <th style="text-align: center">Keterangan</th>
                </tr>
                </thead>
                <?php $i = 1 ?>
                <?php foreach ($querySearch->all() as $instansiPegawai) { ?>
                    <?php /** @var InstansiPegawai $instansiPegawai */ ?>
                    <tr>
                        <td style="text-align: center"><?= $i++ ?></td>
                        <td><?= $instansiPegawai->pegawai->nama ?></td>
                        <td style="text-align: center"><?= @$instansiPegawai->pegawai->golongan->golongan ?></td>
                        <td style="text-align: center"><?= @$instansiPegawai->eselon->nama ?></td>
                        <td><?= $instansiPegawai->getNamaJabatan(false) ?></td>
                        <td style="text-align: center"><?= @$instansiPegawai->getJenisJabatan() ?></td>
                        <td style="text-align: center">
                            <?= $instansiPegawai->isMengisiIki() ? '<i class="fa fa-check" style="color: #00e765"></i>' : '<i class="fa fa-times"></i>' ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } ?>

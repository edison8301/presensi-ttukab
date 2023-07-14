<?php

use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\modules\kinerja\models\KegiatanStatus;

/* @see \app\modules\kinerja\controllers\KegiatanTahunanController::actionIndexBawahanV3() */
/* @var $this yii\web\View */
/* @var $searchModel KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanTahunanUtama \app\modules\kinerja\models\KegiatanTahunan[] */
/* @var $allKegiatanTahunanTambahan \app\modules\kinerja\models\KegiatanTahunan[] */

$this->title = 'Daftar RHK Bawahan Tahun ' . $searchModel->tahun;
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index-v3',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-tahunan/index-bawahan-v3'], 'post'); ?>

<div class="kegiatan-tahunan-index box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar RHK Tahunan <?= $searchModel->nomor_skp; ?></h3>
    </div>
    <div class="box-header">

        <div class="form-group form-inline">
            <div class="input-group form-inline">
                <?= Html::dropDownList('aksi',null,['1'=>'Setuju','4'=>'Tolak'],[
                    'class'=>'form-control',
                    'prompt'=>'- Pilih Aksi -'
                ],[
                    'style'=>'width:30px'
                ]); ?>
            </div>
            <div class="input-group">
                <?= Html::submitButton('<i class="fa fa-check"></i> Terapkan Aksi', [
                    'class' => 'btn btn-success btn-flat',
                    'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                ]); ?>
            </div>
        </div>

    </div>

    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="text-align: center;">
                        <?= Html::checkbox('pilih', false, [
                            'onClick' => 'toggleCheckbox(this)',
                        ]); ?>
                    </th>
                    <th style="text-align: center; width: 50px;">No</th>
                    <th style="text-align: center; width: 250px;">Rencana Hasil Kerja Atasan Yang Diintervensi</th>
                    <th style="text-align: center; width: 300px;">Rencana Kinerja</th>
                    <th style="text-align: center; width: 80px;">Aspek</th>
                    <th style="text-align: center">Indikator Kinerja Individu</th>
                    <th style="text-align: center; width: 100px;">Target</th>
                    <th style="text-align: center; width: 80px;">Status</th>
                    <th style="width: 80px;"></th>
                </tr>
                <tr>
                    <th colspan="9">Utama</th>
                </tr>
                <?php $no=1; foreach ($allKegiatanTahunanUtama as $kegiatanTahunan) { ?>
                    <?= $this->render('_tr-kegiatan-tahunan-v3', [
                        'kegiatanTahunan' => $kegiatanTahunan,
                        'no' => $no++,
                    ]) ?>
                <?php } ?>
                <tr>
                    <th colspan="9">Tambahan</th>
                </tr>
                <?php $no=1; foreach ($allKegiatanTahunanTambahan as $kegiatanTahunan) { ?>
                    <?= $this->render('_tr-kegiatan-tahunan-v3', [
                        'kegiatanTahunan' => $kegiatanTahunan,
                        'no' => $no++,
                    ]) ?>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php Html::endForm(); ?>

<script>
    function toggleCheckbox(element) {
        let arrayCheckbox = document.getElementsByClassName('checkbox-data');
        for(let i=0; i <= arrayCheckbox.length; i++) {
            arrayCheckbox[i].checked = element.checked;
        }
    }
</script>

<?php

use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanHarianUtama KegiatanHarian[] */
/* @var $allKegiatanHarianTambahan KegiatanHarian[] */
/* @var $pagination \yii\data\Pagination */

$this->title = 'Kinerja Harian';
$this->title .= ' : '.$searchModel->getHariTanggal();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-harian/index-bawahan'], 'post',[]); ?>


<div class="box box-primary">
    <div class="box-header">
        <div class="form-group form-inline">
            <div class="input-group form-inline">
                <?= Html::dropDownList('aksi',null,['1'=>'Setuju','4'=>'Tolak'],[
                    'class'=>'form-control',
                    'prompt'=>'- Pilih Aksi -'
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

            <?php
                $totalCount = $pagination->totalCount;
                $begin = $pagination->getPage() * $pagination->pageSize + 1;
                $end = ($pagination->getPage() + 1) * $pagination->pageSize;
                if ($end > $totalCount) {
                    $end = $totalCount;
                }
            ?>

            Menampilkan <b><?= $begin ?>-<?= $end ?></b> dari <b><?= $totalCount ?></b> item.
            <?= $this->render('_table-index-v3', [
                'searchModel' => $searchModel,
                'allKegiatanHarianUtama' => $allKegiatanHarianUtama,
                'allKegiatanHarianTambahan' => $allKegiatanHarianTambahan,
                'pagination' => $pagination,

            ]) ?>

            <?= LinkPager::widget([
                'pagination' => $pagination,
                'firstPageLabel' => 'Awal',
                'lastPageLabel' => 'Akhir',
            ]); ?>

        </div>
    </div>
</div>

<script>
function toggleCheckbox(element) {
    let arrayCheckbox = document.getElementsByClassName('checkbox-data');
    for(let i=0; i <= arrayCheckbox.length; i++) {
        arrayCheckbox[i].checked = element.checked;
    }
}
</script>

<?php

use app\components\Session;
use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\Helper;
use app\modules\kinerja\models\Kinerja;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanHarianUtama KegiatanHarian[] */
/* @var $allKegiatanHarianTambahan KegiatanHarian[] */

$this->title = 'Kinerja Harian';
$this->title .= ' : '.$searchModel->getHariTanggal();
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index', [
    'title' => 'Filter Kinerja Harian',
    'searchModel' => $searchModel,
]); ?>

<div class="box box-primary">
    <div class="box-header with-border">
        <?= Html::beginForm(['/kinerja/kegiatan-harian/index-v4'], 'post'); ?>

        <?php if(KegiatanHarian::accessCreate()) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Kinerja Utama', [
                '/kinerja/kegiatan-harian/create-v4',
                'id_kegiatan_harian_jenis' => KegiatanHarianJenis::UTAMA,
            ], ['class' => 'btn btn-primary btn-flat']) ?>

            <?= Html::a('<i class="fa fa-plus"></i> Kinerja Tambahan', [
                '/kinerja/kegiatan-harian/create-v4',
                'id_kegiatan_harian_jenis' => KegiatanHarian::TAMBAHAN,
            ], ['class' => 'btn btn-primary btn-flat']) ?>

            <?= Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kinerja Harian', [
                'class' => 'btn btn-warning btn-flat',
                'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                'name' => 'aksi',
                'value' => 'yii1'
            ]); ?>
        <?php } ?>
    </div>

    <div class="box-body">

        <?php if ($searchModel->id_pegawai == null) { ?>
            <i>Silahkan pilih pegawai terlebih dahulu</i>
        <?php } ?>

        <?php if ($searchModel->id_pegawai != null) { ?>
            <?= $this->render('_table-index-v3', [
                'searchModel' => $searchModel,
                'allKegiatanHarianUtama' => $allKegiatanHarianUtama,
                'allKegiatanHarianTambahan' => $allKegiatanHarianTambahan,
            ]) ?>
        <?php } ?>

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

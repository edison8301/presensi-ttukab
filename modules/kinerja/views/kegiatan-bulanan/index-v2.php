<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use app\models\User;
use yii\widgets\LinkPager;

/* @see \app\modules\kinerja\controllers\KegiatanBulananController::actionIndexV2() */
/* @var $this yii\web\View */
/* @var $searchModel \app\modules\kinerja\models\KegiatanBulananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $allKegiatanBulanan \app\modules\kinerja\models\KegiatanBulanan[] */

$this->title = 'Kinerja Bulanan';

if($searchModel->mode == 'bawahan') {
    $this->title .= ' Bawahan';
}

$this->title .= ' : '.$searchModel->getNamaBulan().' '.User::getTahun();

$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index-v2',['searchModel'=>$searchModel]); ?>

<div class="kegiatan-bulanan-index box box-primary">
    <div class="box-header">
        <h3 class="box-title">Kinerja Bulanan</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 50px; text-align: center">No</th>
                <th>Kinerja Bulanan</th>
                <th>Aspek</th>
                <th>Indikator Kinerja Individu</th>
                <th style="text-align: center">Target</th>
                <th style="text-align: center">Realisasi</th>
                <th style="text-align: center">% Realisasi</th>
                <th></th>
            </tr>
            <?php if ($searchModel->id_pegawai == null AND User::isAdmin() == true) { ?>
                <tr>
                    <td colspan="8">
                        Silahkan pilih pegawai pada filter untuk menampilkan Kinerja Bulanan
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="8">Kinerja Utama</th>
            </tr>
            <?php $i = 1;$jumlahKinerja = 0;$persenRealisasiTotal = 0 ?>
            <?php foreach($allKegiatanBulanan as $kegiatanBulanan) {
                $jumlahKinerja++;
                echo $this->render('_tr-kegiatan-bulanan-v2', [
                    'kegiatanBulanan' => $kegiatanBulanan,
                    'no' => $i,
                ]);
                $persenRealisasiTotal += $kegiatanBulanan->persen_realisasi;
                $i++;
            } ?>
            <tr>
                <th colspan="8">Kinerja Tambahan</th>
            </tr>
            <?php foreach($allKegiatanBulananTambahan as $kegiatanBulanan) {
                $jumlahKinerja++;
                echo $this->render('_tr-kegiatan-bulanan-v2', [
                    'kegiatanBulanan' => $kegiatanBulanan,
                    'no' => $i,
                ]);
                $persenRealisasiTotal += $kegiatanBulanan->persen_realisasi;
                $i++;
            } ?>

            <?php foreach($allKegiatanBulananPlt as $kegiatanBulanan) {
                $jumlahKinerja++;
                echo $this->render('_tr-kegiatan-bulanan-v2', [
                    'kegiatanBulanan' => $kegiatanBulanan,
                    'no' => $i,
                    'status_plt' => 1,
                ]);
                $persenRealisasiTotal += $kegiatanBulanan->persen_realisasi;
                $i++;
            } ?>

            <?php if($jumlahKinerja == 0) {
                $jumlahKinerja = 1;
            } ?>
            <tr>
                <th style="text-align:center;" colspan="6">JUMLAH</th>
                <th style="text-align: center"><?= Helper::rp($persenRealisasiTotal/$jumlahKinerja,0,1); ?>%</th>
                <th></th>
            </tr>
        </table>
    </div>
</div>

<?php /*
<div class="kegiatan-bulanan-index box box-primary">
    <div class="box-header">
        <h3 class="box-title">Kinerja Bulanan</h3>
    </div>
    <div class="box-body">
        <?= $this->render('_grid-index-v2', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]); ?>
    </div>
</div>
*/ ?>

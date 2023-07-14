<?php

use app\components\Helper;
use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $pegawaiRekapAbsensiSearch app\modules\absensi\models\PegawaiRekapAbsensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Rekap Absensi Pegawai : ' . $pegawaiRekapAbsensiSearch->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search-index', [
    'pegawaiRekapAbsensiSearch' => $pegawaiRekapAbsensiSearch,
    'action' => Url::to(['index'])
]) ?>

<div class="pegawai-rekap-absensi-index box box-primary">


    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', Yii::$app->request->url . '&refresh=1', ['class' => 'btn btn-primary btn-flat', 'onclick' => 'return confirm("Yakin akan merefresh rekap absensi? Proses refresh akan memakan waktu beberapa menit")']) ?>
        <?php //Html::a('<i class="fa fa-print"></i> Export Excel Rekap Absensi', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
        <?php //Html::a('<i class="fa fa-print"></i> Export PDF Rekap Absensi', Yii::$app->request->url.'&export-pdf=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
        <?php if (User::isAdmin() || User::isAdminInstansi()) { ?>
            <?= Html::a('<i class="fa fa-trash"></i> Delete Rekap Absensi', Yii::$app->request->url . '&delete=1', ['class' => 'btn btn-primary btn-flat', 'onclick' => 'return confirm("Yakin akan menghapus data rekap absensi?");']) ?>
        <?php } ?>
    </div>

    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $pegawaiRekapAbsensiSearch,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'nama_pegawai',
                    'format' => 'raw',
                    'value' => function ($data) use ($pegawaiRekapAbsensiSearch) {
                        $output = Html::a(@$data->pegawai->nama, ['/absensi/pegawai/view', 'id' => $data->id_pegawai, 'PegawaiSearch' => ['bulan' => $pegawaiRekapAbsensiSearch->bulan]]) . '<br>';
                        $output .= @$data->pegawai->nip;
                        return $output;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'bulan',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Helper::getBulanSingkat($data->bulan) . '<br>' . $data->tahun;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'jumlah_hari_kerja',
                    'label' => 'Jumlah<br>Hari<br>Kerja',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:80px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'jumlah_hadir',
                    'label' => 'Jumlah<br>Hadir',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:80px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'jumlah_tidak_hadir',
                    'label' => 'Jumlah<br>Tidak<br>Hadir',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:80px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_potongan_fingerprint',
                    'label' => 'Pot<br>FP (%)',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_potongan_kegiatan',
                    'label' => 'Pot<br>Keg (%)',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_potongan_total',
                    'label' => 'Pot<br>Ttl (%)',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'waktu_diperbarui',
                    'label' => 'Waktu<br>Diperbarui',
                    'encodeLabel' => false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center; width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format' => 'raw',
                    'value' => function ($data) use ($pegawaiRekapAbsensiSearch) {
                        $output = Html::a('<i class="fa fa-eye"></i>', ['/absensi/pegawai/view', 'id' => $data->id_pegawai], ['data-toggle' => 'tooltip', 'title' => 'Lihat Rekap']) . ' ';
                        $output .= Html::a('<i class="fa fa-refresh"></i>', ['/absensi/pegawai/refresh-pegawai-rekap-absensi', 'id' => $data->id_pegawai, 'bulan' => $pegawaiRekapAbsensiSearch->bulan], ['data-toggle' => 'tooltip', 'title' => 'Refresh Rekap']) . ' ';

                        return trim($output);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                /*
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => ['style' => 'text-align:center;width:80px']
                ],
                */

            ],
        ]) ?>
    </div>
</div>


<div class="pegawai-rekap-absensi-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">Rekap Data</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <p>Potongan Total Rata-rata</p>
                        <h3><?= round($dataProvider->query->average('persen_potongan_total'), 2) ?></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Potongan Fingerprint Rata-rata</p>
                        <h3><?= round($dataProvider->query->average('persen_potongan_fingerprint'), 2) ?></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Potongan Kegiatan Rata-rata</p>
                        <h3><?= round($dataProvider->query->average('persen_potongan_kegiatan'), 2) ?></h3>
                    </div>
                    <div class="icon">
                        <i class="fa fa-scissors"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-search"></i> Lihat</a>
                </div>
            </div>
        </div>
    </div>
</div>

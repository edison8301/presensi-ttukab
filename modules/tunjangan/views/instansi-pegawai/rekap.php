<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Bulan '.$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun') ?>

<?= $this->render('_filter',[
    'searchModel'=>$searchModel,
    'action'=>Url::to(['/tunjangan/instansi-pegawai/index'])
]); ?>

<div class="instansi-pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', Yii::$app->request->url.'&refresh=1', ['class' => 'btn btn-primary btn-flat','onclick'=>'return confirm("Yakin akan merefresh rekap absensi? Proses refresh akan memakan waktu beberapa menit")']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Perhitungan TPP', Yii::$app->request->url.'&export_pdf_perhitungan=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Pembayaran TPP', Yii::$app->request->url.'&export_pdf_pembayaran=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
    </div>

    <div class="box-body">

    </div>
</div>

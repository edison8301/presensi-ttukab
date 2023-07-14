<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\TunjanganInstansiJenisJabatanKelas;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TunjanganInstansiJenisJabatanKelasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Tunjangan Unit Jenis Jabatan Kelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-unit-jenis-jabatan-kelas-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Data', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function(TunjanganInstansiJenisJabatanKelas $data) {
                    return @$data->instansi->nama;
                },
                'filter' => Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'kategori',
                'format' => 'raw',
                'value' => function(TunjanganInstansiJenisJabatanKelas $data) {
                    return @$data->getNamaKategori();
                },
                'filter' => TunjanganInstansiJenisJabatanKelas::findArrayKategori(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'value' => function(TunjanganInstansiJenisJabatanKelas $data) {
                    return @$data->getNamaJenisJabatan();
                },
                'filter' => ['1'=>'Struktural','2'=>'Fungsional','3'=>'Pelaksana'],
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'kelas_jabatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nilai_tpp',
                'format' => 'raw',
                'value' => function(TunjanganInstansiJenisJabatanKelas $data) {
                    return Helper::rp($data->nilai_tpp,0);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'attribute' => 'beban_kerja_persen',
                'header' => 'Beban <br> Kerja',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getEditable('beban_kerja_persen');
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'prestasi_kerja_persen',
                'header' => 'Prestasi <br> Kerja',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getEditable('prestasi_kerja_persen');
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'kondisi_kerja_persen',
                'header' => 'Kondisi <br> Kerja',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getEditable('kondisi_kerja_persen');
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tempat_bertugas_persen',
                'header' => 'Tempat <br> Bertugas',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getEditable('tempat_bertugas_persen');
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'kelangkaan_profesi_persen',
                'header' => 'Kelangkaan <br> Profesi',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->getEditable('kelangkaan_profesi_persen');
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>

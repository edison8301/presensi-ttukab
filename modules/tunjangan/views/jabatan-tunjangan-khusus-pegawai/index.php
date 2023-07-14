<?php

use app\components\Helper;
use app\modules\tunjangan\models\JabatanTunjanganKhususPegawai;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tunjangan\models\JabatanTunjanganKhususPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pegawai Dengan Tunjangan Jabatan Khusus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-khusus-pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai Dengan Tunjangan Jabatan Khusus', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php //Html::a('<i class="fa fa-print"></i> Export Excel Tunjangan Jabatan  Khusus Pegawai', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => function(JabatanTunjanganKhususPegawai $data) {
                    return @$data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_jabatan_tunjangan_khusus_jenis',
                'format' => 'raw',
                'value' => function(JabatanTunjanganKhususPegawai $data) {
                    return @$data->jabatanTunjanganKhususJenis->nama;
                },
                'filter' => \app\modules\tunjangan\models\JabatanTunjanganKhususJenis::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function(JabatanTunjanganKhususPegawai $data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function(JabatanTunjanganKhususPegawai $data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function(JabatanTunjanganKhususPegawai $data) {
                    return @$data->instansi->nama;
                },
                'filter' => \app\models\Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center; width: 200px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
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

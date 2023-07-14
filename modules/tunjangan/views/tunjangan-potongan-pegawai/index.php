<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\User;
use app\modules\tunjangan\models\TunjanganPotongan;
use app\modules\tunjangan\models\TunjanganPotonganPegawai;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tunjangan\models\TunjanganPotonganPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Tunjangan Potongan Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-potongan-pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Tunjangan Potongan Pegawai', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Tunjangan Potongan Pegawai', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'attribute' => 'nama_pegawai',
                'value' => function($data) {

                    $text = @$data->pegawai->nama.'<br>'.@$data->pegawai->nip;
                    return Html::a($text,['/tunjangan/pegawai/view','id' => $data->id_pegawai]);
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left; width: 36%'],
            ],
            [
                'attribute' => 'id_tunjangan_potongan',
                'format' => 'raw',
                'filter' => TunjanganPotongan::getList(),
                'value' => function($data) {
                    return @$data->tunjanganPotongan->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'header' => 'Potongan',
                'format' => 'raw',
                'value' => function($data) {
                    if (User::isAdmin()) {
                        return Html::a(number_format(@$data->tunjanganPotongan->persenPotongan,2),['tunjangan-potongan/view','id' => $data->id_tunjangan_potongan]).' %';
                    }
                    return number_format(@$data->tunjanganPotongan->persenPotongan,2);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'header' => 'Unit Kerja',
                'format' => 'raw',
                'filter' => Instansi::getList(),
                'value' => function (TunjanganPotonganPegawai $data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left; width:200px'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>

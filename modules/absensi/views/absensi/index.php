<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\absensi\models\AbsensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Absensi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="absensi-index box box-primary">

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'kode_unit_kerja',
            'kode_pegawai',
            'tanggal_absensi',
            'jam_absensi',
            'waktu_absensi',
            'waktu_dibuat',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>

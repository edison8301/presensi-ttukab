<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\KegiatanBulananBreakdownSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kegiatan Bulanan Breakdowns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulanan-breakdown-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kegiatan Bulanan Breakdown', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_kegiatan_tahunan_detil',
            'kegiatan:ntext',
            'kuantitas',
            'id_satuan_kuantitas',
            // 'penilaian_kualitas',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

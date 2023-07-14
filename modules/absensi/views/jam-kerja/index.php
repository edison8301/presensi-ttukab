<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\JamKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jam Kerjas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jam Kerja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_shift_kerja',
            'hari',
            'jenis',
            'nama',
            // 'jam_mulai_pindai',
            // 'jam_selesai_pindai',
            // 'jam_mulai_normal',
            // 'jam_selesai_normal',
            // 'status_wajib',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

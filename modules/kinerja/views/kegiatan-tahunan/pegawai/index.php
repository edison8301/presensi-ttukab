<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\LabelKegiatan;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Tahunan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-tahunan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Tahunan', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan Tahunan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= $this->render('//kegiatan-tahunan/partials/_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>

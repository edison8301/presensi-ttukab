<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanBulananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Bulanan';

$this->title = 'Daftar Kegiatan Bulanan';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getNamaBulan().' '.User::getTahun();

$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index-v2',['searchModel'=>$searchModel]); ?>

<div class="kegiatan-bulanan-index box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Kegiatan Bulanan</h3>
    </div>
    <?php /*
    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    */ ?>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $this->render('_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>

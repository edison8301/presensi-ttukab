<?php

use app\components\Helper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanBulananSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Bulanan ' . Helper::getBulanLengkap($bulan);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"> Filter Bulan </h3>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => ['kegiatan-bulanan/subordinat-index'],
        'method' => 'get',
    ]); ?>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= Html::dropDownList('bulan', $bulan, Helper::getBulanList(), ['onchange' => 'this.form.submit()', 'class' => 'form-control']); ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="kegiatan-bulanan-index box box-primary">
    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= $this->render('//kegiatan-bulanan/partials/_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>

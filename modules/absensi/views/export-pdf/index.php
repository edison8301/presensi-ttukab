<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\ExportPdfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Export Pdfs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-pdf-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Export Pdf', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_instansi',
            'bulan',
            'tahun',
            'hash',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

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

<?php $form = ActiveForm::begin([
    'action' => ['kegiatan-bulanan/pegawai-index'],
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <?= Html::dropDownList('bulan', $bulan, Helper::getBulanList(), ['onchange' => 'this.form.submit()', 'class' => 'form-control']); ?>
            </div>
        </div>
    </div>  
</div>
 <?php ActiveForm::end(); ?>

<div class="kegiatan-bulanan-index box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Bulanan', ['/kegiatan-bulanan/create'], ['class' => 'btn btn-success btn-flat']); ?>
    </div>
    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= $this->render('//kegiatan-bulanan/_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>

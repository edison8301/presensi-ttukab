<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\LabelKegiatan;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Tahunan';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'action' => ['kegiatan-harian/pegawai-index'],
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-2">
               <?= Html::textInput('tahun', date('Y'), ['onchange' => 'this.form.submit()', 'class' => 'form-control']); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


<div class="kegiatan-tahunan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Tahunan', ['/kegiatan-tahunan/create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan Tahunan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= $this->render('//kegiatan-tahunan/_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>

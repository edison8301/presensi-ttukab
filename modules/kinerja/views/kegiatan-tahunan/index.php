<?php

use app\modules\kinerja\models\KegiatanTahunanSearch;
use yii\helpers\Html;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;

/* @var $this yii\web\View */
/* @var $searchModel KegiatanTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Tahunan';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : ' . $searchModel->tahun;

$this->params['breadcrumbs'][] = $this->title;

if(isset($debug)==false) {
    $debug = false;
}

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<?= Html::beginForm(['/kinerja/kegiatan-tahunan/index'], 'post'); ?>

<div class="kegiatan-tahunan-index box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Kegiatan Utama SKP <?= $searchModel->nomor_skp; ?></h3>
    </div>

    <div class="box-header">
        <?= $this->render('_menu-index',[
              'searchModel'=>$searchModel
        ]); ?>
    </div>

    <div class="box-body">
        <?= $this->render('_grid-index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'debug'=>$debug
        ]); ?>
    </div>
</div>
<?php Html::endForm(); ?>

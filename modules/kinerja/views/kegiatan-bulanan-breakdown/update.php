<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KegiatanBulananBreakdown */

$this->title = 'Update Kegiatan Bulanan Breakdown: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulanan Breakdowns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kegiatan-bulanan-breakdown-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

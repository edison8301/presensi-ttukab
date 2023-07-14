<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KegiatanBulananBreakdown */

$this->title = 'Create Kegiatan Bulanan Breakdown';
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Bulanan Breakdowns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-bulanan-breakdown-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

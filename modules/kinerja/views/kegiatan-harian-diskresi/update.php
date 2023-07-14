<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanHarianDiskresi */

$this->title = "Sunting Kegiatan Harian Diskresi";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harian Diskresis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-harian-diskresi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

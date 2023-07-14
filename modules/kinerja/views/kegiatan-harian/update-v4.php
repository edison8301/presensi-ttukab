<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanHarian */
/* @var $referrer string */

$this->title = "Sunting Kegiatan Harian";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harians', 'url' => ['index-v4']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-harian-update">

    <?= $this->render('_form-v4', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

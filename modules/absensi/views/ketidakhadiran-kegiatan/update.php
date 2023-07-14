<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\KetidakhadiranKegiatan */
/* @var $referrer string */

$this->title = "Sunting Ketidakhadiran Kegiatan";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Kegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ketidakhadiran-kegiatan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

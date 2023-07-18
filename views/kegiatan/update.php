<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Kegiatan */

$this->title = "Sunting Kegiatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="kegiatan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Catatan */

$this->title = "Sunting Kegiatan Tahunan Catatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan Catatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="catatan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

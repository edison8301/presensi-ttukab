<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\absensi\models\Absensi */
/* @var $referrer string */

$this->title = 'Update Absensi: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="absensi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=>$referrer
    ]) ?>

</div>

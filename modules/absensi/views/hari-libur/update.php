<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\HariLibur */
/* @var $referrer string */

$this->title = 'Sunting Hari Libur';
$this->params['breadcrumbs'][] = ['label' => 'Hari Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hari-libur-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer' => $referrer
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerja */

$this->title = 'Sunting Jam Kerja: ';
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jam-kerja-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

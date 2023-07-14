<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\kinerja\models\UnitKerja */

$this->title = 'Create Perangkat Daerah';
$this->params['breadcrumbs'][] = ['label' => 'Perangkat Daerahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-kerja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

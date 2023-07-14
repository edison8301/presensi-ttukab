<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Peta */

$this->title = "Sunting Peta";
$this->params['breadcrumbs'][] = ['label' => 'Petas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="peta-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer,
        'mode' => $mode,
    ]) ?>

</div>

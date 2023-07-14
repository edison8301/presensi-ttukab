<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiInduk */

$this->title = "Sunting Instansi Induk";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Induks', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-induk-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

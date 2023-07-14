<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Artikel */

$this->title = "Sunting Artikel";
$this->params['breadcrumbs'][] = ['label' => 'Artikels', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="artikel-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

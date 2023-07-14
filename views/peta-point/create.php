<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PetaPoint */

$this->title = "Tambah Peta Point";
$this->params['breadcrumbs'][] = ['label' => 'Peta Points', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peta-point-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

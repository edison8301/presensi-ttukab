<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Checkinout */
/* @var $referrer string */

$this->title = "Tambah Checkinout";
$this->params['breadcrumbs'][] = ['label' => 'Checkinouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

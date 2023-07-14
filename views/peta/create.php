<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Peta */

$this->title = "Tambah Peta";
$this->params['breadcrumbs'][] = ['label' => 'Petas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peta-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer,
        'mode' => $mode,
    ]) ?>

</div>

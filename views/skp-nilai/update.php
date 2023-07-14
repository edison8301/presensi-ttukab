<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SkpNilai */

$this->title = "Sunting Skp Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Skp Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="skp-nilai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

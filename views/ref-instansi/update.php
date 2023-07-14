<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RefInstansi */
/* @var $referrer string */


$this->title = "Sunting Ref Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Ref Instansis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ref-instansi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

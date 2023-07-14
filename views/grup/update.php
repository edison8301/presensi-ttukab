<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Grup */
/* @var $referrer string */

$this->title = "Sunting Grup";
$this->params['breadcrumbs'][] = ['label' => 'Grups', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="grup-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

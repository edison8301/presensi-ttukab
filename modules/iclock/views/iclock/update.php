<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\iclock\Iclock */
/* @var $referrer string */

$this->title = "Sunting Iclock";
$this->params['breadcrumbs'][] = ['label' => 'Iclocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="iclock-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

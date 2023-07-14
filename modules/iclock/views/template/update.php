<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $referrer string */

$this->title = "Sunting Template";
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="template-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

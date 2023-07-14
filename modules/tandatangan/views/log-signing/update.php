<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\LogSigning */

$this->title = "Sunting Log Signing";
$this->params['breadcrumbs'][] = ['label' => 'Log Signings', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="log-signing-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

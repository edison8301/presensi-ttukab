<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tandatangan\models\LogSigning */

$this->title = "Tambah Log Signing";
$this->params['breadcrumbs'][] = ['label' => 'Log Signings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-signing-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

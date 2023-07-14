<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JabatanEvjab */
/* @var $referrer string */


$this->title = "Sunting Jabatan Evjab";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Evjabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jabatan-evjab-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

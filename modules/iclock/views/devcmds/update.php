<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\iclock\models\Devcmds */
/* @var $referrer string */

$this->title = "Sunting Devcmds";
$this->params['breadcrumbs'][] = ['label' => 'Devcmds', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="devcmds-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

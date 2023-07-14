<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\RefVariabelObjektif */
/* @var $referrer string */

$this->title = "Sunting Ref Variabel Objektif";
$this->params['breadcrumbs'][] = ['label' => 'Ref Variabel Objektifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ref-variabel-objektif-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

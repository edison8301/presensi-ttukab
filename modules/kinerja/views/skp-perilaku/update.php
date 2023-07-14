<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpPerilaku */

$this->title = "Sunting Skp Perilaku";
$this->params['breadcrumbs'][] = ['label' => 'Skp Perilakus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="skp-perilaku-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pengumuman */
/* @var $referrer string */

$this->title = "Sunting Pengumuman";
$this->params['breadcrumbs'][] = ['label' => 'Pengumuman', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pengumuman-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

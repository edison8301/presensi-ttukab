<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKelas */
/* @var $referrer string */

$this->title = "Sunting Tunjangan Kelas";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-kelas-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

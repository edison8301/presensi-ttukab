<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\iclock\Iclock */
/* @var $referrer string */


$this->title = "Tambah Iclock";
$this->params['breadcrumbs'][] = ['label' => 'Iclocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iclock-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

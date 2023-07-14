<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Grup */
/* @var $referrer string */

$this->title = "Tambah Grup";
$this->params['breadcrumbs'][] = ['label' => 'Grups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grup-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

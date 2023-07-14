<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $referrer string */


$this->title = "Tambah Template";
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

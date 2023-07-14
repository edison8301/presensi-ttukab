<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserInstansi */
/* @var $referrer string */

$this->title = "Tambah User Instansi";
$this->params['breadcrumbs'][] = ['label' => 'User Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-instansi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

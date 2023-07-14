<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RefInstansi */
/* @var $referrer string */

$this->title = "Tambah Ref Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Ref Instansis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-instansi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

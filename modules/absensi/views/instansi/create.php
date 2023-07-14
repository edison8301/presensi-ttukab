<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Instansi */
/* @var $referrer string */

$this->title = "Tambah Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Instansis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

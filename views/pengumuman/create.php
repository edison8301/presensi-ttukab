<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pengumuman */
/* @var $referrer string */

$this->title = "Tambah Pengumuman";
$this->params['breadcrumbs'][] = ['label' => 'Pengumuman', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengumuman-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

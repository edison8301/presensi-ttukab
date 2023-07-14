<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKelas */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Kelas";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-kelas-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

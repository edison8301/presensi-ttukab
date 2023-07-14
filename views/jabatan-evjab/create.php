<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JabatanEvjab */
/* @var $referrer string */

$this->title = "Tambah Jabatan Evjab";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Evjabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-evjab-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

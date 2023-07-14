<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotongan */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Potongan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-potongan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

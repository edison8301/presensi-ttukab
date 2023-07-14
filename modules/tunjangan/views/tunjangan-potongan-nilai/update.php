<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganNilai */
/* @var $referrer string */

$this->title = "Sunting Tunjangan Potongan Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongan Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-potongan-nilai-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

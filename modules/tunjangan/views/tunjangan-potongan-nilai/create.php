<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotonganNilai */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Potongan Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongan Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-potongan-nilai-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

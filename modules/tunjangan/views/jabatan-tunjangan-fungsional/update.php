<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganFungsional */
/* @var $referrer string */

$this->title = "Sunting Jabatan Tunjangan Fungsional";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Fungsionals', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jabatan-tunjangan-fungsional-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

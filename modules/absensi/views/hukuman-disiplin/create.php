<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\HukumanDisiplin */
/* @var $referrer string */

$this->title = "Tambah Hukuman Disiplin";
$this->params['breadcrumbs'][] = ['label' => 'Hukuman Disiplins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hukuman-disiplin-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>

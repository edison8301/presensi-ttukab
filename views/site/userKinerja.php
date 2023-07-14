<?php
use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Dashboard Tahun '.Yii::$app->session->get('tahun', date('Y'));

?>

<?= $this->render('_filter',['filter' => $filter]) ?>


<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Data Tunjangan Kinerja</h3>
	</div>
	<div class="box-body">
		<table class="table">
		<thead>
		<tr>
			<th>No</th>
			<th>NIP</th>
			<th>Nama</th>
			<th style="text-align: center">Tunjangan Kinerja</th>
			<th style="text-align: center">Tunjangan Absensi</th>
			<th style="text-align: center">Tunjangan Total</th>
		</tr>
		</thead>
	
		</table>
	</div>
</div>
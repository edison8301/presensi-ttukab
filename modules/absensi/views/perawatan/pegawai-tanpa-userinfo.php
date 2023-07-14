<?php

use app\models\Pegawai;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title ="Pegawai Tanpa Userinfo";

?>

<div class="box box-primary">
	<div class="box-header">
		<h3 class="box-title">Pegawai Tanpa Userinfo</h3>
	</div>

	<div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Userinfo', ['/absensi/pegawai/set-jumlah-userinfo-v2'], ['class' => 'btn btn-primary btn-flat','onclick'=>'return confirm("Yakin akan merefresh data userinfo?")']) ?>
    </div>

	<div class="box-body">
		<table class="table table-bordered">
		<tr>
			<th style="text-align: center">No</th>
			<th style="text-align: center">Nama</th>
			<th style="text-align: center">NIP</th>
			<th style="text-align: center">Jumlah Userinfo</th>
			<th style="text-align: center">Perangkat Daerah</th>
            <th></th>
		</tr>
		<?php
			$query = Pegawai::find();
			$query->andWhere('status_hapus is null');
			$query->andWhere('jumlah_userinfo = 0');
			$query->orderBy(['id_instansi'=>SORT_ASC]);
		?>
		<?php $i=1; foreach($query->all() as $pegawai) { ?>
		<tr>
			<td style="text-align: center"><?= $i ?></td>
			<td><?= $pegawai->nama; ?></td>
			<td><?= $pegawai->nip; ?></td>
			<td style="text-align: center"><?= $pegawai->jumlah_userinfo; ?></td>
			<td><?= @$pegawai->instansi->nama; ?></td>
            <td style="text-align: center">
                <?= Html::a('<i class="fa fa-edit"></i>', [
                    '/iclock/userinfo/update-v2',
                    'id' => $pegawai->id,
                ], [
                    'data-toggle' => 'tooltip',
                    'title' => 'Edit Userinfo',
                    'class' => 'btn btn-success btn-flat btn-sm'
                ]) ?>
            </td>
		</tr>
		<?php $i++; }  ?>
		</table>

	</div>

</div>



<?php

/* @var $this yii\web\View */

?>
	<div class="text-center" style="font-weight: bold">
			STRUKTUR JABATAN<br/>
            <?= $model->nama; ?>
	</div>

	<div>&nbsp;</div>

	<div class="box-body">
        <table class="table">
            <tr>
                <th style="text-align: center;">Nama Jabatan</th>
                <th style="text-align: center;">Bidang</th>
                <th style="text-align: center;">Jenis Jabatan</th>
                <th style="text-align: center; width: 50px">Nilai<br/>Jabatan</th>
                <th style="text-align: center; width: 50px">Kelas<br/>Jabatan</th>
                <th style="text-align: center">Pegawai</th>
                <?php /*
                <th style="text-align: center">Mutasi/<br/>Promosi</th>
                */ ?>
                <th style="text-align: center">Status<br/>Verifikasi</th>
            </tr>
            <?php foreach($model->manyJabatanKepala as $jabatan) { ?>
                <?= $this->render('_tr-jabatan-pdf', [
                        'jabatan' => $jabatan,
                        'level' => 0,
                        'searchModel'=>$searchModel
                ]); ?>
            <?php } ?>
        </table>
    </div>
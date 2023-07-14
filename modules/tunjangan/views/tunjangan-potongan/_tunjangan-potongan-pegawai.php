<?php 

use app\components\Helper;
use yii\helpers\Html;


?>


<div class="tunjangan-potongan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai Dengan Potongan <?= $model->getLabelJenisPotongan(); ?></h3>
    </div>

    <div class="box-body">

		<?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai', ['tunjangan-potongan-pegawai/create','id_tunjangan_potongan' => $model->id,'tahun' => $model->tahun,'bulan' => $model->bulan], ['class' => 'btn btn-success btn-flat']) ?>

		<div>&nbsp;</div>

    	<table class="table table-bordered">
    		<tr>
    			<th class="text-center" width="50px">No</th>
    			<th class="text-center">Nama Pegawai</th>
    			<th class="text-center">Jabatan</th>
    			<th class="text-center">&nbsp;</th>
    		</tr>
    		<?php $no=1; foreach ($model->findAllTunjanganPotonganPegawai() as $tunjanganPotonganPegawai){ ?>	
    		<tr>
    			<td class="text-center"><?= $no++; ?></td>
    			<td>
    				<?= @$tunjanganPotonganPegawai->pegawai->nama; ?> <br>
    				<?= $tunjanganPotonganPegawai->pegawai->nip; ?>
    			</td>
    			<td class="text-center"><?= @$tunjanganPotonganPegawai->pegawai->instansiPegawai->jabatan->nama; ?></td>
    			<td class="text-center">
    				<?= Html::a("<i class='fa fa-trash'></i>",['tunjangan-potongan-pegawai/delete','id' => $tunjanganPotonganPegawai->id],['data-method' => 'post','data-confirm' => 'apa anda yakin untuk menghapus data ini?']); ?>
    			</td>
    		</tr>
    		<?php } ?>
    	</table>
    </div>
</div>

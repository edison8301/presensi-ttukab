<?php

use yii\helpers\Html;

$query = \app\models\InstansiPegawai::find();
$query->andWhere([
    'status_hapus'=>0
]);

$query->andWhere('tanggal_mulai = :tanggal',[
    ':tanggal' => '2020-01-02'
]);

?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai Bermasalah</h3>
    </div>
    <div class="box-body">
        <table class="table">
            <tr>
                <th>Id</th>
                <th>Id Pegawai</th>
                <th>Nama Pegawai</th>
                <th>NIP Pegawai</th>
                <th>Tanggal Mulai</th>
                <th>SKP</th>
            </tr>

            <?php foreach($query->all() as $data) { ?>
                <tr>
                    <td><?= $data->id; ?></td>
                    <td><?= $data->id_pegawai; ?></td>
                    <td>
                        <?= Html::a(@$data->pegawai->nama,[
                            '/pegawai/view',
                            'id'=>$data->id_pegawai
                        ]); ?>
                    </td>
                    <td><?= @$data->pegawai->nip; ?></td>
                    <td><?= $data->tanggal_mulai; ?></td>
                    <td>
                        <?= Html::a('<i class="fa fa-file"></i>',[
                            '/kinerja/instansi-pegawai-skp/index',
                            'InstansiPegawaiSkpSearch[id_pegawai]'=>$data->id_pegawai
                        ]); ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>



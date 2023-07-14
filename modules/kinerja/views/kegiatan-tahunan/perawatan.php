<?php


use app\modules\kinerja\models\KegiatanTahunan;
use yii\helpers\Html;

$query = KegiatanTahunan::find();
$query->andWhere(['tahun'=>$tahun]);
$query->offset($offset);
$query->with(['instansiPegawai','pegawai']);
$i = 0;
foreach($query->all() as $data) {
    if($data->id_pegawai != $data->instansiPegawai->id_pegawai) {
        print $data->id . ' - ' . $data->id_kegiatan_status . ' - ' . $data->pegawai->nama . ' - ' . $data->pegawai->nip;
        print " - ";
        print Html::a('Delete',[
            '/kinerja/kegiatan-tahunan/delete',
            'id'=>$data->id
        ],[
            'data-method' => 'post'
        ]);
        print "<br/>";
        $i++;
    }
}
print "Jumlah: " . $i . "<br/>";

<?php



namespace app\modules\api2\models;

use Yii;

class Pegawai extends \app\models\Pegawai
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['jabatan'] = function () {
            return @$this->instansiPegawaiBerlaku->namaJabatan;
        };

        $fields['nama_instansi'] = function () {
            return @$this->instansiPegawaiBerlaku->instansi->nama;
        };

        $fields['atasan'] = function () {
            return @$this->instansiPegawaiBerlaku->atasan;
        };

        $fields['golongan'] = function () {
            return @$this->golongan;
        };

        $fields['url_foto'] = function () {
            return $this->getUrlFoto();
        };

        return $fields;
    }

    public function getUrlFoto()
    {
        $pathFoto = Yii::getAlias('@app/web/uploads/foto/').$this->foto;

        if ($this->foto != null AND file_exists($pathFoto)) {
            return $pathFoto;
        }

        return Yii::getAlias('@app/web/images/default-user.jpeg');
    }


}

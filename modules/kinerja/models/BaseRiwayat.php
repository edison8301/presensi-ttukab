<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * Base riwayat
 * Seluruh tabel riwayat harus extend dari class ini
 * Class ini menyediakan fungsi dan fitur yang dibutuhkan
 * pada tabel riwayat
 * Tabel yang extend class ini harus memiliki attribute :
 * @property int $id
 * @property int $id_riwayat_jenis
 * @property string $keterangan
 * @property string $waktu_dibuat
 * @property string $username_pembuat
 * @property string $nama_user
 */
abstract class BaseRiwayat extends \yii\db\ActiveRecord
{

    abstract public static function getForeignKey();

    public function rules()
    {
        return [
            [['id_riwayat_jenis', 'keterangan', 'username_pembuat', 'nama_user'], 'required'],
            [['id_riwayat_jenis'], 'integer'],
            [['waktu_dibuat'], 'safe'],
            [['keterangan', 'username_pembuat', 'nama_user'], 'string', 'max' => 255],
        ];
    }

    protected function setKeterangan()
    {
        $this->keterangan = "User $this->username_pembuat " . $this->getAksi() . " Kegiatan";
    }

    protected function getAksi()
    {
        if ($this->id_riwayat_jenis === RiwayatJenis::TAMBAH) {
            return "Menambah";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::SUNTING) {
            return "Menyunting";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::HAPUS) {
            return "Menghapus";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::SETUJU) {
            return "Menyetujui";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::KONSEP) {
            return "Mengubah status menjadi konsep";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::PERIKSA) {
            return "Mengubah status menjadi periksa";
        } elseif ($this->id_riwayat_jenis === RiwayatJenis::TOLAK) {
            return "Menolak";
        }
    }

    public function setIdRiwayatJenis($id_kegiatan_status = null)
    {
        if ($id_kegiatan_status === null) {
            $this->id_riwayat_jenis = $id_kegiatan_status;
        } else {
            $this->id_riwayat_jenis = RiwayatJenis::getJenisRiwayatStatus()[$id_kegiatan_status];
        }
    }

    public static function createRiwayat($model, $id_riwayat_jenis, $id_kegiatan_status = null)
    {
        return true;
        $riwayat = new static([
            'waktu_dibuat' => date('Y-m-d H:i:s'),
            'username_pembuat' => @Yii::$app->user->identity->username,
            'nama_user' => @Yii::$app->user->identity->nama,
        ]);
        $riwayat->{static::getForeignKey()} = $model->id;
        if ($id_kegiatan_status === null) {
            $riwayat->id_riwayat_jenis = $id_riwayat_jenis;
        } else {
            $riwayat->setIdRiwayatJenis($id_kegiatan_status);
        }
        $riwayat->setKeterangan();
        return $riwayat->save(false);
    }
}

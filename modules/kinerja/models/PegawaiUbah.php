<?php

namespace app\modules\kinerja\models;

use app\models\Pegawai;
use Yii;

/**
 * This is the model class for table "pegawai_ubah".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_pegawai_ubah_jenis
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class PegawaiUbah extends \yii\db\ActiveRecord
{
    public $nama_pegawai;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_ubah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_pegawai_ubah_jenis', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['id_pegawai', 'id_pegawai_ubah_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['keterangan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_pegawai_ubah_jenis' => 'Id Pegawai Ubah Jenis',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PegawaiUbahQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiUbahQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai']);
    }
}

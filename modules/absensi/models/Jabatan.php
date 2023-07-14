<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "ref_jabatan".
 *
 * @property int $id
 * @property int $id_jenis_jabatan
 * @property int $id_instansi
 * @property string $bidang
 * @property string $subbidang
 * @property string $nama
 * @property int $kelas_jabatan
 * @property int $persediaan_pegawai
 */
class Jabatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_jenis_jabatan', 'id_instansi', 'kelas_jabatan', 'persediaan_pegawai'], 'integer'],
            [['nama', 'kelas_jabatan'], 'required'],
            [['bidang', 'subbidang', 'nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jenis_jabatan' => 'Id Jenis Jabatan',
            'id_instansi' => 'Id Instansi',
            'bidang' => 'Bidang',
            'subbidang' => 'Subbidang',
            'nama' => 'Nama',
            'kelas_jabatan' => 'Kelas Jabatan',
            'persediaan_pegawai' => 'Persediaan Pegawai',
        ];
    }

    /**
     * @inheritdoc
     * @return JabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JabatanQuery(get_called_class());
    }
}

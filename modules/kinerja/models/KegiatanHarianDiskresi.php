<?php

namespace app\modules\kinerja\models;

use app\models\Pegawai;
use Yii;

/**
 * This is the model class for table "kegiatan_harian_diskresi".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal
 * @property string $keterangan
 * @property Pegawai $pegawai
 */
class KegiatanHarianDiskresi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_harian_diskresi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal'], 'required'],
            [['id_pegawai'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string'],
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
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }
}

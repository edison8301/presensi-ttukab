<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tunjangan_kelas".
 *
 * @property int $id
 * @property int $kelas_jabatan
 * @property string $jumlah_tunjangan
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class TunjanganKelas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_kelas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kelas_jabatan'], 'required'],
            [['kelas_jabatan'], 'integer'],
            [['jumlah_tunjangan'], 'number'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kelas_jabatan' => 'Kelas Jabatan',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TunjanganKelasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TunjanganKelasQuery(get_called_class());
    }

    public function getManyTunjanganKelasKomponen()
    {
        return $this->hasMany(TunjanganKelasKomponen::class,['id_tunjangan_kelas'=>'id']);
    }

    public function findOrCreateTunjanganKelasKomponen($params)
    {
        return TunjanganKelasKomponen::findOrCreate([
            'id_tunjangan_kelas'=>$this->id,
            'id_tunjangan_komponen'=>@$params['id_tunjangan_komponen']
        ]);
    }

    public function updateJumlahTunjangan()
    {
        $jumlah_tunjangan = $this->getManyTunjanganKelasKomponen()
            ->andWhere(['status_aktif'=>1])
            ->sum('jumlah_tunjangan');

        $this->updateAttributes([
            'jumlah_tunjangan'=>$jumlah_tunjangan
        ]);

    }
}

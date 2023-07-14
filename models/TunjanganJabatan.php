<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tunjangan_jabatan".
 *
 * @property int $id
 * @property int $id_jabatan
 * @property string $jumlah_tunjangan
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 */
class TunjanganJabatan extends \yii\db\ActiveRecord
{
    public $id_instansi;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan'], 'required'],
            [['id_jabatan'], 'integer'],
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
            'id_jabatan' => 'Nama Jabatan',
            'jumlah_tunjangan' => 'Jumlah Tunjangan',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TunjanganJabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TunjanganJabatanQuery(get_called_class());
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class,['id'=>'id_jabatan']);
    }

    public function getManyTunjanganJabatanKomponen()
    {
        return $this->hasMany(TunjanganJabatanKomponen::class,['id_tunjangan_jabatan'=>'id']);
    }

    public function findOrCreateTunjanganJabatanKomponen($params)
    {
        return TunjanganJabatanKomponen::findOrCreate([
            'id_tunjangan_jabatan'=>$this->id,
            'id_tunjangan_komponen'=>@$params['id_tunjangan_komponen']
        ]);
    }

    public function getNamaJabatan()
    {
        return @$this->jabatan->nama;
    }

    public function updateJumlahTunjangan()
    {
        $jumlah_tunjangan = $this->getManyTunjanganJabatanKomponen()
            ->andWhere(['status_aktif'=>1])
            ->sum('jumlah_tunjangan');

        $this->updateAttributes([
            'jumlah_tunjangan'=>$jumlah_tunjangan
        ]);

    }
}

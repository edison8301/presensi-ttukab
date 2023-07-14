<?php

namespace app\modules\kinerja\models;

use app\models\Golongan;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\modules\tukin\models\Pegawai;
use Yii;

/**
 * This is the model class for table "pegawai_skp".
 *
 * @property int $id
 * @property int $id_instansi_pegawai
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property int $id_jabatan
 * @property int $id_golongan
 * @property int $id_eselon
 * @property int $nomor
 * @property string $tahun
 * @property int $id_atasan
 * @property string $tanggal_berlaku
 * @property int $status_hapus
 * @property Instansi $instansi
 * @property Golongan $golongan
 */
class PegawaiSkp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_skp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai','tahun','nomor'],'required'],
            [['id_instansi_pegawai', 'id_pegawai', 'id_instansi', 'id_jabatan',
                'id_golongan', 'id_eselon', 'nomor', 'id_atasan', 'status_hapus'
            ], 'integer'],
            [['tahun', 'tanggal_berlaku', 'nama_jabatan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_pegawai' => 'Id Instansi Pegawai',
            'id_pegawai' => 'Pegawai',
            'id_instansi' => 'Instansi',
            'id_jabatan' => 'Jabatan',
            'id_golongan' => 'Golongan',
            'id_eselon' => 'Eselon',
            'nomor' => 'Nomor',
            'tahun' => 'Tahun',
            'id_atasan' => 'Id Atasan',
            'tanggal_berlaku' => 'Tanggal Berlaku',
            'status_hapus' => 'Status Hapus',
        ];
    }

    public static function find()
    {
        $query = parent::find();
        $query->andWhere(['pegawai_skp.status_hapus'=>0]);

        return $query;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class,['id'=>'id_jabatan']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class,['id'=>'id_eselon']);
    }

    public function getGolongan()
    {
        return $this->hasOne(Golongan::class,['id'=>'id_golongan']);
    }

    public static function generate2018($pegawai)
    {
        $query = InstansiPegawai::find();
        $query->andWhere([
            'id_pegawai'=>$pegawai->id,
            'tahun'=>'2018'
        ]);

        $nomor = 1;
        foreach ($query->all() as $instansiPegawai) {

            $model = PegawaiSkp::find()->andWhere([
                'id_pegawai'=>$instansiPegawai->id,
                'tahun'=>$instansiPegawai->tahun,
                'tanggal_berlaku'=>$instansiPegawai->tanggal_berlaku
            ])->one();

            if($model===null) {
                $model = new PegawaiSkp;
            }

            $model->attributes = $instansiPegawai->attributes;
            $model->nomor = $nomor;

            $model->save();

            $nomor++;
        }


    }

    public static function generate2019($pegawai)
    {

        $model = PegawaiSkp::find()->andWhere([
            'id_pegawai'=>$pegawai->id,
            'tahun'=>'2019',
            'tanggal_berlaku'=>'2019-01-01'
        ])->one();

        if($model===null) {
            $model = new PegawaiSkp;
            $model->id_pegawai = $pegawai->id;
            $model->tahun = 2019;
            $model->tanggal_berlaku = '2019-01-01';
        }

        $model->id_instansi = $pegawai->id_instansi;
        $model->id_jabatan = $pegawai->id_jabatan;
        $model->id_eselon = $pegawai->id_eselon;
        $model->id_golongan = $pegawai->id_golongan;
        $model->nomor = 1;

        $model->save();

    }

}

<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jabatan_evjab".
 *
 * @property int $id
 * @property int $id_jenis_jabatan
 * @property int $id_instansi
 * @property int $id_instansi_bidang
 * @property string $nama
 * @property int $nilai_jabatan
 * @property int $kelas_jabatan
 * @property int $persediaan_pegawai
 * @property int $id_induk
 * @property int $status_hapus
 * @property string $waktu_hapus
 * @property int $id_user_hapus
 * @property int $nomor
 */
class JabatanEvjab extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_evjab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jenis_jabatan', 'id_instansi', 'id_instansi_bidang', 'nilai_jabatan', 'kelas_jabatan', 'persediaan_pegawai', 'id_induk', 'status_hapus', 'id_user_hapus', 'nomor'], 'integer'],
            [['nama', 'kelas_jabatan'], 'required'],
            [['waktu_hapus'], 'safe'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jenis_jabatan' => 'Jenis Jabatan',
            'id_instansi' => 'Instansi',
            'id_instansi_bidang' => 'Bidang',
            'nama' => 'Nama',
            'nilai_jabatan' => 'Nilai Jabatan',
            'kelas_jabatan' => 'Kelas Jabatan',
            'persediaan_pegawai' => 'Persediaan Pegawai',
            'id_induk' => 'Id Induk',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
            'nomor' => 'Nomor',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getInstansiBidang()
    {
        return $this->hasOne(InstansiBidang::class,['id'=>'id_instansi_bidang']);
    }

    public function getJenisJabatan()
    {
        if ((int) $this->id_jenis_jabatan === 1) {
            return "Struktural";
        }

        if ((int) $this->id_jenis_jabatan === 2) {
            return "Non Struktural (JFT)";
        }

        if ((int) $this->id_jenis_jabatan === 3) {
            return "Non Struktural (JFU)";
        }
    }

    public static function getList($params = [])
    {
        $query = self::find();

        if(@$params['id_instansi']!=null) {
            $query->andFilterWhere([
                'id_instansi'=>@$params['id_instansi']
            ]);
        }

        if(@$params['id_instansi_bidang']!=null) {
            $query->andWhere('id_jenis_jabatan = 1 OR id_jenis_jabatan != 1
                AND id_instansi_bidang = :id_instansi_bidang',[
                    ':id_instansi_bidang'=>@$params['id_instansi_bidang']
            ]);
        }

        return ArrayHelper::map($query->all(),'id','nama');

    }
}

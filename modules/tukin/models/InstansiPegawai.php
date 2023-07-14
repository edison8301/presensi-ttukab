<?php

namespace app\modules\tukin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "instansi_pegawai".
 *
 * @property int $id
 * @property int $id_ref_jabatan
 * @property string $tahun
 * @property int $id_instansi
 * @property int $id_pegawai
 * @property int $id_atasan
 * @property int $id_golongan
 * @property int $id_eselon
 * @property string $nama_jabatan
 * @property string $tanggal_berlaku
 * @property int $lama
 * @property int $status_hapus
 * @property string $waktu_dihapus
 *
 * @property Instansi $instansi
 */
class InstansiPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_pegawai';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ref_jabatan', 'id_instansi', 'id_pegawai', 'id_atasan', 'id_golongan', 'id_eselon', 'lama', 'status_hapus'], 'integer'],
            [['tahun', 'tanggal_berlaku', 'waktu_dihapus'], 'safe'],
            [['id_instansi', 'id_pegawai', 'tanggal_berlaku'], 'required'],
            [['nama_jabatan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_ref_jabatan' => 'Id Ref Jabatan',
            'tahun' => 'Tahun',
            'id_instansi' => 'Id Instansi',
            'id_pegawai' => 'Id Pegawai',
            'id_atasan' => 'Id Atasan',
            'id_golongan' => 'Id Golongan',
            'id_eselon' => 'Id Eselon',
            'nama_jabatan' => 'Nama Jabatan',
            'tanggal_berlaku' => 'Tanggal Berlaku',
            'lama' => 'Lama',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InstansiPegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstansiPegawaiQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai'])
            ->inverseOf('allInstansiPegawai');
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getAtasan()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_atasan']);
    }

    public function getGolongan()
    {
        return $this->hasOne(Golongan::class, ['id' => 'id_golongan']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class, ['id' => 'id_eselon']);
    }

    public static function getListInstansi($id_pegawai, $map = false)
    {
        $query = static::find()
            ->aktif()
            ->andWhere(['id_pegawai' => $id_pegawai])
            ->with('instansi');
        $list = [];
        if ($map === true) {
            return ArrayHelper::map($query->all(), 'id', function ($model) {
                return $model->nama_jabatan . ' - ' .@$model->instansi->nama;
            });
        }
        foreach ($query->all() as $model) {
            $list[] = ['id' => $model->id, 'name' => $model->nama_jabatan . ' - ' .@$model->instansi->nama];
        }
        return $list;
    }}

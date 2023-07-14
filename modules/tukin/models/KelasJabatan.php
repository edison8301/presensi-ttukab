<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "kelas_jabatan".
 *
 * @property int $id
 * @property int $kelas_jabatan
 * @property int $nilai_minimal
 * @property Pegawai $manyPegawai
 * @property Jabatan $manyJabatan
 * @property int $nilai_maksimal
 */
class KelasJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kelas_jabatan';
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
            [['kelas_jabatan', 'nilai_minimal', 'nilai_maksimal'], 'required'],
            [['kelas_jabatan', 'nilai_minimal', 'nilai_maksimal'], 'integer'],
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
            'nilai_minimal' => 'Nilai Minimal',
            'nilai_maksimal' => 'Nilai Maksimal',
        ];
    }

    public function getManyJabatan()
    {
        return $this->hasMany(Jabatan::class, ['kelas_jabatan' => 'kelas_jabatan']);
    }

    public function getManyPegawai()
    {
        return $this->hasMany(Pegawai::class, ['id_jabatan' => 'id'])
            ->via('manyJabatan');
    }

    public function countPegawai()
    {
        return count($this->manyPegawai);
    }

    public function getNilaiTengah()
    {
        return $this->nilai_maksimal;
    }

    public function getBesaranMinimal()
    {
        return $this->nilai_minimal * Yii::$app->params['idrp'];
    }

    public function getBesaranMaksimal()
    {
        return $this->nilai_maksimal * Yii::$app->params['idrp'];
    }

    public function getBesaranTengah()
    {
        return $this->getNilaiTengah() * Yii::$app->params['idrp'];
    }
}

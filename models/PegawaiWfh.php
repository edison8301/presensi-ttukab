<?php

namespace app\models;

use Yii;
use yii2mod\query\ArrayQuery;

/**
 * This is the model class for table "pegawai_wfh".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal
 * @property string $keterangan
 * @property int $status_aktif
 */
class PegawaiWfh extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_wfh';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal'], 'required'],
            [['id_pegawai', 'status_aktif'], 'integer'],
            [['tanggal'], 'safe'],
            [['keterangan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'status_aktif' => 'Status Aktif',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai']);
    }

    public static function findOneFromArray($params = [], $array =[])
    {
        $query = new ArrayQuery();
        $query->from($array);

        if(@$params['id_pegawai'] != null) {
            $query->andWhere(['id_pegawai' => @$params['id_pegawai']]);
        }

        if(@$params['tanggal'] != null) {
            $query->andWhere(['tanggal' => @$params['tanggal']]);
        }

        return $query->one();
    }

    public function getNamaStatusAktif()
    {
        if($this->status_aktif == 1) {
            return "Aktif";
        }

        if($this->status_aktif == 0) {
            return "Tidak";
        }

        return null;
    }
}

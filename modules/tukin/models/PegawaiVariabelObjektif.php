<?php

namespace app\modules\tukin\models;

use app\components\Helper;
use Yii;

/**
 * This is the model class for table "pegawai_variabel_objektif".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_ref_variabel_objektif
 * @property int $bulan_mulai
 * @property int $bulan_selesai
 * @property string $tahun
 * @property double $tarif
 *
 * @property Pegawai $pegawai
 * @property string $range
 * @property RefVariabelObjektif $refVariabelObjektif
 */
class PegawaiVariabelObjektif extends \yii\db\ActiveRecord
{
    public $variabel_objektif;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_variabel_objektif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_ref_variabel_objektif', 'bulan_mulai', 'bulan_selesai', 'tahun'], 'required'],
            [['tarif'], 'default', 'value' => 0],
            [['id_pegawai', 'id_ref_variabel_objektif', 'bulan_mulai', 'bulan_selesai'], 'integer'],
            [['tahun'], 'safe'],
            [['tarif'], 'number'],
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
            'id_ref_variabel_objektif' => 'Variabel Objektif',
            'bulan_mulai' => 'Bulan Mulai',
            'bulan_selesai' => 'Bulan Selesai',
            'tahun' => 'Tahun',
            'tarif' => 'Tarif',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefVariabelObjektif()
    {
        return $this->hasOne(RefVariabelObjektif::class, ['id' => 'id_ref_variabel_objektif']);
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    public function getRange()
    {
        return Helper::getBulanLengkap($this->bulan_mulai) . ' - ' . Helper::getBulanLengkap($this->bulan_selesai);
    }

    public static function accessCreate()
    {
        return User::isAdmin() || \app\models\User::isInstansi();
    }
}

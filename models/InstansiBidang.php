<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "instansi_bidang".
 *
 * @property int $id
 * @property int $id_instansi
 * @property string $nama
 */
class InstansiBidang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_bidang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi'], 'integer'],
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
            'id_instansi' => 'Id Instansi',
            'nama' => 'Nama',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getNamaLengkap()
    {
        return $this->nama.' - '.$this->instansi->nama;
    }

    public static function getList($params=[])
    {
        $query = self::find();

        if(@$params['id_instansi']) {
            $query->andWhere(['id_instansi'=>@$params['id_instansi']]);
        }

        return ArrayHelper::map($query->all(),'id','namaLengkap');
    }
}

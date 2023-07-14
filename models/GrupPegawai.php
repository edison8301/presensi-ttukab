<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grup_pegawai".
 *
 * @property int $id
 * @property int $id_grup
 * @property int $id_pegawai
 */
class GrupPegawai extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grup_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_grup', 'id_pegawai'], 'required'],
            [['id_grup', 'id_pegawai'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_grup' => 'Grup',
            'id_pegawai' => 'Pegawai',
        ];
    }

    public function getGrup()
    {
        return $this->hasOne(Grup::class, ['id' => 'id_grup']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }
}

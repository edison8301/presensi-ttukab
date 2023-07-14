<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "instansi_lokasi".
 *
 * @property int $id
 * @property string $nama
 */
class InstansiLokasi extends \yii\db\ActiveRecord
{
    const UMUM = 1;
    const LEPAR_PONGOK_DAN_SELAT_NASIK = 2;
    const PULAU_LEPAR = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_lokasi';
    }

    public static function findArrayDropDownList()
    {
        return ArrayHelper::map(InstansiLokasi::find()->all(),'id','nama');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
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
            'nama' => 'Nama',
        ];
    }
}

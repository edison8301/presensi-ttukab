<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peta_point".
 *
 * @property int $id
 * @property int $id_peta
 * @property int $urutan
 * @property string $latitude
 * @property string $longitude
 */
class PetaPoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peta_point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_peta'], 'required'],
            [['id_peta', 'urutan'], 'integer'],
            [['latitude', 'longitude'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_peta' => 'Id Peta',
            'urutan' => 'Urutan',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    public function setUrutan()
    {
        $model = PetaPoint::find()
            ->count();

        return $model+1;
    }
}

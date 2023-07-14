<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_instansi".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_instansi
 */
class UserInstansi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_instansi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_instansi'], 'required'],
            [['id_user', 'id_instansi'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'User',
            'id_instansi' => 'Perangkat Daerah',
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['id' => 'id_instansi']);
    }
}

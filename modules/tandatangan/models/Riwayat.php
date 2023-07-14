<?php

namespace app\modules\tandatangan\models;

use Yii;

/**
 * This is the model class for table "riwayat".
 *
 * @property int $id
 * @property int $id_berkas
 * @property int $id_user
 * @property int $id_riwayat_jenis
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Riwayat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'riwayat';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_tandatangan');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_berkas', 'id_riwayat_jenis'], 'required'],
            [['id_berkas', 'id_user', 'id_riwayat_jenis'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_berkas' => 'Id Berkas',
            'id_user' => 'Id User',
            'id_riwayat_jenis' => 'Id Riwayat Jenis',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}

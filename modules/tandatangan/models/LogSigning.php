<?php

namespace app\modules\tandatangan\models;

use app\models\Instansi;
use Yii;

/**
 * This is the model class for table "log_signing".
 *
 * @property int $id
 * @property int $id_berkas
 * @property string $nama
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class LogSigning extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_signing';
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
            [['id_berkas', 'nama'], 'required'],
            [['id_berkas'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'id_berkas' => 'Berkas',
            'nama' => 'Nama',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function getBerkas()
    {
        return $this->hasOne(Berkas::class, ['id' => 'id_berkas']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi'])
            ->via('berkas');
    }
}

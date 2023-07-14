<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "kegiatan_harian_tambahan".
 *
 * @property int $id
 * @property string $nama
 */
class KegiatanHarianTambahan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_harian_tambahan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

    public static function getList()
    {
        $query = KegiatanHarianTambahan::find();

        if (\app\models\User::isPegawai()) {
            $query->andWhere(['NOT IN', 'id', [2,3,4]]);
        }

        return ArrayHelper::map($query->all(),'id','nama');
    }
}

<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_jam_kerja_status".
 *
 * @property int $id
 * @property string $nama
 */
class KetidakhadiranJamKerjaStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_jam_kerja_status';
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
        return ArrayHelper::map(KetidakhadiranJamKerjaStatus::find()->all(),'id','nama');
    }

    public function getLabelNama()
    {
        return '<span class="label label-primary">'.$this->nama.'</span>';
    }
}

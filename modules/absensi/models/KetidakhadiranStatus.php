<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_status".
 *
 * @property integer $id
 * @property string $nama
 */
class KetidakhadiranStatus extends \yii\db\ActiveRecord
{
    const SETUJU = 1;
    const PROSES = 2;
    const TOLAK = 3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_status';
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
        return ArrayHelper::map(KetidakhadiranStatus::find()->all(),'id','nama');
    }

    public function getLabelNama()
    {
        if((int) $this->id === self::SETUJU) {
            return '<span class="label label-success">'.$this->nama.'</span>';
        }

        if((int) $this->id === self::PROSES) {
            return '<span class="label label-warning">'.$this->nama.'</span>';
        }

        if((int) $this->id === self::TOLAK) {
            return '<span class="label label-danger">'.$this->nama.'</span>';
        }
    }
}

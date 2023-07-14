<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_panjang_status".
 *
 * @property int $id
 * @property string $nama
 */
class KetidakhadiranPanjangStatus extends \yii\db\ActiveRecord
{
    const SETUJU = 1;
    const PROSES = 2;
    const TOLAK = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_panjang_status';
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
        return ArrayHelper::map(KetidakhadiranKegiatanStatus::find()->all(),'id','nama');
    }

    public function getLabelNama()
    {
        if($this->id == 1) {
            return '<span class="label label-success">'.$this->nama.'</span>';
        }

        if($this->id == 2) {
            return '<span class="label label-warning">'.$this->nama.'</span>';
        }

        if($this->id == 3) {
            return '<span class="label label-danger">'.$this->nama.'</span>';
        }
    }
}

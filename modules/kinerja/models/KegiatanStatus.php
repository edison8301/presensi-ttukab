<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "kegiatan_status".
 *
 * @property int $id
 * @property string $kode_kegiatan_status
 * @property string $nama
 */
class KegiatanStatus extends \yii\db\ActiveRecord
{
    const SETUJU = 1;
    const KONSEP = 2;
    const PERIKSA = 3;
    const TOLAK = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_kegiatan_status', 'nama'], 'required'],
            [['kode_kegiatan_status'], 'string', 'max' => 10],
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
            'kode_kegiatan_status' => 'Kode Kegiatan Status',
            'nama' => 'Nama',
        ];
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }

    public function getLabel()
    {
        if($this->id==self::SETUJU)
            return '<span class="label label-success">'.$this->nama.'</span>';

        if($this->id==self::KONSEP)
            return '<span class="label label-info">'.$this->nama.'</span>';

        if($this->id==self::PERIKSA)
            return '<span class="label label-warning">'.$this->nama.'</span>';

        if($this->id==self::TOLAK)
            return '<span class="label label-danger">'.$this->nama.'</span>';

        return null;
    }

    public function getLabelIcon()
    {
        if($this->id==self::SETUJU)
            return '<span class="label label-success" data-toggle="tooltip" title="Setuju"><i class="fa fa-check"></i></span>';

        if($this->id==self::KONSEP)
            return '<span class="label label-info" data-toggle="tooltip" title="Konsep"><i class="fa fa-pencil-square-o"></i></span>';

        if($this->id==self::PERIKSA)
            return '<span class="label label-warning" data-toggle="tooltip" title="Periksa"><i class="fa fa-search"></i></span>';

        if($this->id==self::TOLAK)
            return '<span class="label label-danger" data-toggle="tooltip" title="Tolak"><i class="fa fa-remove"></i></span>';
    }
}

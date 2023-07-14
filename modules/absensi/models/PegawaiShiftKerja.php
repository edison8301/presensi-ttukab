<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "pegawai_shift_kerja".
 *
 * @property integer $id
 * @property integer $id_pegawai
 * @property integer $id_shift_kerja
 * @property string $tanggal_berlaku
 */
class PegawaiShiftKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai_shift_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai','id_shift_kerja','tanggal_berlaku'],'required'],
            [['id_pegawai', 'id_shift_kerja'], 'integer'],
            [['tanggal_berlaku','status_hapus'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     * @return PegawaiShiftKerjaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiShiftKerjaQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_shift_kerja' => 'Shift Kerja',
            'tanggal_berlaku' => 'Tanggal Berlaku',
        ];
    }

    public function getShiftKerja()
    {
        return $this->hasOne(ShiftKerja::className(), ['id' => 'id_shift_kerja']);
    }

    public function softDelete()
    {
        $this->status_hapus = date('Y-m-d H:i:s');

        return $this->save();
    }

}

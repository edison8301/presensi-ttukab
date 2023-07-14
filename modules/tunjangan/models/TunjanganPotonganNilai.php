<?php

namespace app\modules\tunjangan\models;

use Yii;
use app\components\Helper;
use yii\helpers\Html;

/**
 * This is the model class for table "tunjangan_potongan_nilai".
 *
 * @property int $id
 * @property int $id_tunjangan_potongan
 * @property double $nilai persen potongan
 * @property int $tanggal_mulai
 * @property int $tanggal_selesai
 */
class TunjanganPotonganNilai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_potongan_nilai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tunjangan_potongan'], 'required'],
            [['id_tunjangan_potongan', 'tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['nilai'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tunjangan_potongan' => 'Id Tunjangan Potongan',
            'nilai' => 'Nilai',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
        ];
    }

    public function getLabelTanggalSelesai()
    {
        if ($this->tanggal_selesai == '9999-12-31') {
            return Html::tag('span','Masih Berlaku',['class' => 'label label-success btn-flat btn-xs']);
        }
        return Helper::getTanggal($this->tanggal_selesai);
    }

    public function setWaktuSelesai()
    {
        if ($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }
    }

    public function updateWaktuSebelum()
    {
        if ($this->tanggal_selesai == '9999-12-31') {
            $sebelum =  TunjanganPotonganNilai::find()
                ->orderBy(['id' => SORT_DESC])
                ->andWhere(['<>','id',$this->id])
                ->one();
            if ($sebelum !== null) {
                $sebelum->tanggal_selesai = date('Y-m-d');
                $sebelum->save();
            }
        }
        return true;
    }

}

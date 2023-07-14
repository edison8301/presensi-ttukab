<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_tahunan_fungsional".
 *
 * @property int $id
 * @property int $id_kegiatan_tahunan
 * @property string $butir_kegiatan
 * @property string $output
 * @property string $angka_kredit
 */
class KegiatanTahunanFungsional extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_tahunan_fungsional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kegiatan_tahunan'], 'required'],
            [['id_kegiatan_tahunan'], 'integer'],
            [['angka_kredit'], 'number'],
            [['butir_kegiatan', 'output'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Id Kegiatan Tahunan',
            'butir_kegiatan' => 'Butir Kegiatan',
            'output' => 'Output',
            'angka_kredit' => 'Angka Kredit',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan_ketidakhadiran".
 *
 * @property int $id
 * @property string $nip
 * @property int $id_kegiatan_ketidakhadiran_jenis
 * @property string $penjelasan
 * @property string $checktime
 * @property string $foto_pendukung
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class KegiatanKetidakhadiran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_ketidakhadiran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nip', 'id_kegiatan_ketidakhadiran_jenis', 'penjelasan', 'checktime', 'foto_pendukung'], 'required'],
            [['id_kegiatan_ketidakhadiran_jenis'], 'integer'],
            [['penjelasan'], 'string'],
            [['checktime', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nip', 'foto_pendukung'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nip' => 'Nip',
            'id_kegiatan_ketidakhadiran_jenis' => 'Id Kegiatan Ketidakhadiran Jenis',
            'penjelasan' => 'Penjelasan',
            'checktime' => 'Checktime',
            'foto_pendukung' => 'Foto Pendukung',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}

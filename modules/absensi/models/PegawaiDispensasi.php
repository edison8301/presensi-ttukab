<?php

namespace app\modules\absensi\models;

use app\modules\absensi\models\PegawaiDispensasiJenis;
use Yii;
use app\models\Pegawai;
use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "pegawai_dispensasi".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal_mulai
 * @property string $tanggal_akhir
 * @property int $status_hapus
 * @property string $user_pembuat
 * @property string $waktu_dibuat
 * @property string $user_pengubah
 * @property string $waktu_diubah
 */
class PegawaiDispensasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai_dispensasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal_mulai'], 'required'],
            [['id_pegawai', 'status_hapus'], 'integer'],
            [['tanggal_mulai', 'tanggal_akhir', 'waktu_dibuat', 'waktu_diubah'], 'safe'],
            [['user_pembuat', 'user_pengubah','keterangan'], 'string', 'max' => 255],
            [['id_pegawai_dispensasi_jenis'], 'integer']
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'waktu_dibuat',
                'updatedAtAttribute' => 'waktu_diubah',
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_pembuat',
                'updatedByAttribute' => 'user_pengubah',
            ],
            [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status_hapus' => true
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_akhir' => 'Tanggal Akhir',
            'status_hapus' => 'Status Hapus',
            'user_pembuat' => 'User Pembuat',
            'waktu_dibuat' => 'Waktu Dibuat',
            'user_pengubah' => 'User Pengubah',
            'waktu_diubah' => 'Waktu Diubah',
            'id_pegawai_dispensasi_jenis' => 'Jenis'
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai'])->inverseOf('pegawaiDispensasi');
    }

    public function getPegawaiDispensasiJenis()
    {
        return $this->hasOne(PegawaiDispensasiJenis::class, ['id' => 'id_pegawai_dispensasi_jenis']);
    }

    public function getUserPembuat()
    {
        return $this->hasOne(User::class, ['id' => 'user_pembuat']);
    }

    public function getUserPengubah()
    {
        return $this->hasOne(User::class, ['id' => 'user_pengubah']);
    }
}

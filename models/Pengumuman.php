<?php

namespace app\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengumuman".
 *
 * @property int $id
 * @property string $judul
 * @property string $teks
 * @property int $status
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $user_pembuat
 * @property string $waktu_dibuat
 * @property int $user_pengubah
 * @property string $waktu_diubah
 * @property int $status_hapus
 * @property string $waktu_dihapus
 */
class Pengumuman extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pengumuman';
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
    public function rules()
    {
        return [
            [['judul', 'teks', 'tanggal_mulai', 'tanggal_selesai'], 'required'],
            [['teks'], 'string'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['user_pembuat', 'user_pengubah'], 'integer'],
            [['judul'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul' => 'Judul',
            'teks' => 'Teks',
            'status' => 'Status',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'user_pembuat' => 'User Pembuat',
            'waktu_dibuat' => 'Waktu Dibuat',
            'user_pengubah' => 'User Pengubah',
            'waktu_diubah' => 'Waktu Diubah',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
        ];
    }

    public function attributeHints()
    {
        $teksHint = <<<HTML
        Template yang tersedia <br>
            {pegawai} : akan diganti menjadi nama pegawai / nama admin jika yang login adalah admin  <br>
            {instansi} : akan diganti menjadi nama instansi dari pegawai / operator instansi jika instansi tersedia <br>
            {pegawai/instansi} : akan diganti menjadi nama pegawai jika pegawai yang login dan menjadi instansi jika yang login adalah instansi <br>
HTML;
        return [
            'teks' => $teksHint,
        ];
    }

    /**
     * @inheritdoc
     * @return PengumumanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PengumumanQuery(get_called_class());
    }

    public function beforeSoftDelete()
    {
        $this->waktu_dihapus = date('Y-m-d H:i:s');
        return true;
    }

    public static function getAllPengumumanAktif()
    {
        return static::find()
            ->berlaku()
            ->aktif()
            ->all();
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

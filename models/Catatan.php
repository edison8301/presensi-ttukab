<?php

namespace app\models;

use app\components\Session;
use Yii;

/**
 * This is the model class for table "kegiatan_tahunan_catatan".
 *
 * @property int $id
 * @property int $id_kegiatan_tahunan
 * @property int $id_induk
 * @property int $id_user
 * @property string $catatan
 * @property string $waktu_buat
 */
class Catatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'catatan', 'waktu_buat'], 'required'],
            [['id_kegiatan_tahunan', 'id_kegiatan_harian', 'id_induk', 'id_user'], 'integer'],
            [['catatan'], 'string'],
            [['waktu_buat'], 'safe'],
            [['id_kegiatan_tahunan'], 'required', 'when' => function($model) {
                return $this->id_kegiatan_tahunan !== null;
            }],
            [['id_kegiatan_harian'], 'required', 'when' => function($model) {
                return $this->id_kegiatan_harian !== null;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan' => 'Kegiatan Tahunan',
            'id_kegiatan_harian' => 'Kegiatan Harian',
            'id_induk' => 'Id Induk',
            'id_user' => 'Id User',
            'catatan' => 'Catatan',
            'waktu_buat' => 'Waktu Buat',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getNamaUser()
    {
        if(@$this->user->id_user_role == 2) {
            $user = $this->user;
            return @$user->pegawai->nama;
        }

        return @$this->user->username;
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isPegawai() == true AND $this->id_user == Session::getIdUser()) {
            return true;
        }

        return false;
    }
}

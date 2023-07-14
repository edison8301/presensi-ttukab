<?php

namespace app\models;

use app\components\Session;
use Yii;

/**
 * This is the model class for table "pegawai_tunjangan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tahun
 * @property int $bulan
 * @property int $status_kunci
 * @property string $waktu_kunci
 * @property string $waktu_buat
 * @property int $id_user_buat
 * @property int $waktu_ubah
 * @property int $id_user_ubah
 * @property int $status_hapus
 * @property int $waktu_hapus
 * @property int $id_user_hapus
 */
class PegawaiTunjangan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_tunjangan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tahun', 'bulan'], 'required'],
            [['id_pegawai', 'bulan', 'status_kunci', 'id_user_buat', 'waktu_ubah', 'id_user_ubah', 'status_hapus', 'waktu_hapus', 'id_user_hapus'], 'integer'],
            [['tahun', 'waktu_kunci', 'waktu_buat'], 'safe'],
            [['id_pegawai', 'tahun', 'bulan'], 'unique', 'targetAttribute' => ['id_pegawai', 'tahun', 'bulan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'tahun' => 'Tahun',
            'bulan' => 'Bulan',
            'status_kunci' => 'Status Kunci',
            'waktu_kunci' => 'Waktu Kunci',
            'waktu_buat' => 'Waktu Buat',
            'id_user_buat' => 'Id User Buat',
            'waktu_ubah' => 'Waktu Ubah',
            'id_user_ubah' => 'Id User Ubah',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PegawaiTunjanganQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiTunjanganQuery(get_called_class());
    }

    public static function findOrCreate($params)
    {
        $model = PegawaiTunjangan::findOne([
            'id_pegawai' => $params['id_pegawai'],
            'tahun' => $params['tahun'],
            'bulan' => $params['bulan']
        ]);

        if($model===null) {
            $model = new PegawaiTunjangan($params);
            $model->waktu_buat = date('Y-m-d H:i:s');
            $model->id_user_buat = Session::getIdUser();

            if($model->save()==false) {
                print "app\models\PegawaiTunjangan on line 93";
                print_r($model->getErrors());
                die();
            }
        }

        return $model;
    }
}

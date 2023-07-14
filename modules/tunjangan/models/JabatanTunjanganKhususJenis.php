<?php

namespace app\modules\tunjangan\models;

use app\components\Session;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jabatan_tunjangan_khusus_jenis".
 *
 * @property int $id
 * @property string $nama
 */
class JabatanTunjanganKhususJenis extends \yii\db\ActiveRecord
{
    const GURU = 1;
    const KEPALA_SEKOLAH = 2;
    const PENGAWAS_SEKOLAH = 3;
    const DOKTER_SUBSPESIALIS = 4;
    const DOKTER_SPESIALIS = 5;
    const DIREKTUR_RSP_IR_SOEKARNO_SPESIALIS = 6;
    const DIREKTUR_RSP_IR_SOEKARNO_NON_SPESIALIS = 7;
    const TEMPAT_BERTUGAS_LEPAR_PONGOK = 10;
    const TEMPAT_BERTUGAS_KEPULAUAN_PONGOK = 11;
    const TEMPAT_BERTUGAS_SELAT_NASIK = 12;
    const SUPIR = 14;
    const BENDAHARA_SAMPAI_20M = 15;
    const BENDAHARA_LEBIH_20M = 16;
    const ASISTEN_SEKRETARIS_DAERAH= 17;
    const STAF_AHLI_GUBERNUR = 18;
    const DIREKTUR_RSJ_SPESIALIS = 19;
    const DIREKTUR_RSJ_NON_SPESIALIS = 20;
    const GURU_BERSERTIFIKASI = 21;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan_tunjangan_khusus_jenis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = static::find();

        if(Session::isAdminInstansi()) {
            $query->andWhere([
                'id'=>['16']
            ]);
        }

        if(Session::isInstansi()) {
            $query->andWhere([
                'id'=>['16']
            ]);
        }


        return ArrayHelper::map($query->all(),'id','nama');
    }
}

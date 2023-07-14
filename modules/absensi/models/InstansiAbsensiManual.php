<?php

namespace app\modules\absensi\models;

use app\models\Instansi;
use Yii;

/**
 * This is the model class for table "instansi_absensi_manual".
 *
 * @property int $id
 * @property int $id_instansi
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $status_hapus
 * @property string $waktu_hapus
 * @property int $id_user_hapus
 * @property Instansi $instansi
 */
class InstansiAbsensiManual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_absensi_manual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi', 'tanggal_mulai'], 'required'],
            [['id_instansi', 'status_hapus', 'id_user_hapus'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'waktu_hapus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi' => 'Instansi',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InstansiAbsensiManualQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new InstansiAbsensiManualQuery(get_called_class());
        $query->andWhere('status_hapus = 0');
        return $query;

    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function getNamaInstansi()
    {
        return @$this->instansi->nama;
    }

    /**
     * @param array $params
     * @return bool
     */
    public static function isManual(array $params)
    {
        if(@$params['id_instansi']==null) {
            print "InstansiAbsensiManual::isManual() => Params id_instansi tidak boleh kosong";
            die();
        }

        $tahun = date('Y');
        $bulan = date('n');

        if(@$params['tahun']!=null) {
            $tahun = @$params['tahun'];
        }

        if(@$params['bulan']!=null) {
            $bulan = @$params['bulan'];
        }

        $datetime = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$bulan.'-01');

        $query = InstansiAbsensiManual::find();

        $query->andWhere([
            'id_instansi'=>@$params['id_instansi']
        ]);

        $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
            ':tanggal' => $datetime->format('Y-m-15')
        ]);

        $model = $query->one();

        if($model!==null)  {
            return true;
        }

        return false;

    }
}

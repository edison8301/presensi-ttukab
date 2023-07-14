<?php

namespace app\modules\tunjangan\models;

use Yii;
use app\components\Helper;
use app\models\KegiatanStatus;
use yii\base\Model;

/**
 * This is the model class for table "condition_inode_usage".
 *
 * @property string $host
 * @property string $filesystem
 * @property string $inodes
 * @property string $iused
 * @property string $ifree
 * @property string $mounted_on
 * @property string $created_at
 */
class Dasbor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $mode = 'pegawai';
    public $bulan;
    public $tahun;
    public $tanggal;
    public $id_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_instansi'],'integer'],
            [['tanggal'],'safe'],
            [['tahun'], 'number', 'min'=>1960,'max'=>2999],
            [['bulan'], 'number', 'min'=>1, 'max'=>12],
            [['id_instansi'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun' => 'Tahun',
            'bulan' => 'Bulan'
        ];
    }

    public function getManyPegawai()
    {
        return $this->hasMany(Pegawai::className(), [
            'id_instansi' => 'id_instansi'
        ]);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), [
            'id_instansi' => 'id_instansi'
        ]);
    }

    public function setSession()
    {
        if ($this->tahun != null)
            Yii::$app->session->set('tahun',$this->tahun);

        if ($this->bulan != null)
            Yii::$app->session->set('bulan',$this->bulan);

        Yii::$app->session->set('kode_instansi',$this->kode_instansi);
    }

    public function getPersenRealisasiKegiatanBulanan()
    {
        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        $query->andWhere(['bulan'=>$this->bulan]);
        $query->andWhere(['kegiatan_tahunan.tahun'=>User::getTahun()]);
        $query->andWhere(['kegiatan_tahunan.id_pegawai'=>User::getIdPegawai()]);

        $total_kegiatan = 0;
        $total_persen = 0;

        foreach($query->all() as $data) {
            $total_persen = $total_persen + $data->getPersenRealisasi();
            $total_kegiatan++;
        }

        $rata_persen = 0;

        if($total_kegiatan!=0)
        {
            $rata_persen = $total_persen/$total_kegiatan;
        }

        return $rata_persen;


    }

    public function getHariTanggal()
    {
        return Helper::getHari($this->tanggal).', '.Helper::getTanggalSingkat($this->tanggal);
    }


    public function queryKegiatanHarian($params)
    {
        $query = KegiatanHarian::find();
        $query->joinWith(['kegiatanTahunan']);

        if(isset($params['id_pegawai']))
        {
            $query->andWhere(['kegiatan_tahunan.id_pegawai'=>$params['id_pegawai']]);
        }

        if(isset($params['tanggal']))
        {
            $query->andWhere(['tanggal'=>$params['tanggal']]);
        }

        return $query;
    }

    public function countKegiatanHarian($params)
    {
        $query = $this->queryKegiatanHarian($params);

        return $query->count();
    }

    public function queryKegiatanBulanan($params)
    {
        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_status'=>KegiatanStatus::SETUJU]);

        if(isset($params['id_pegawai']))
        {
            $query->andWhere(['kegiatan_tahunan.id_pegawai'=>$params['id_pegawai']]);
        }

        if(isset($params['bulan']))
        {
            $query->andWhere(['bulan'=>$params['bulan']]);
        }

        return $query;
    }

    public function countKegiatanBulanan($params)
    {
        $query = $this->queryKegiatanBulanan($params);

        return $query->count();
    }

    public function sumKegiatanBulanan($params)
    {
        $query = $this->queryKegiatanBulanan($params);

        $sum = $query->sum('kegiatan_bulanan.'.$params['attribute']);
        
        if($sum==null)
            return 0;

        return $sum;
    }

    public function queryKegiatanTahunan($params)
    {
        $query = KegiatanTahunan::find();
        $query->andWhere('id_induk IS NULL');
        $query->andWhere(['id_kegiatan_status'=>KegiatanStatus::SETUJU]);

        if(isset($params['id_pegawai']))
        {
            $query->andWhere(['id_pegawai'=>$params['id_pegawai']]);
        }

        if(isset($params['tahun']))
        {
            $query->andWhere(['tahun'=>User::getTahun()]);
        }

        return $query;
    }

    public function countKegiatanTahunan($params)
    {
        $query = $this->queryKegiatanTahunan($params);

        return $query->count();
    }

    public function sumKegiatanTahunan($params)
    {
        $query = $this->queryKegiatanTahunan($params);

        $sum = $query->sum($params['attribute']);
        
        if($sum==null)
            return 0;

        return $sum;
    }
}

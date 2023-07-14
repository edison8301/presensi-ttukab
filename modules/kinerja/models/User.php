<?php

namespace app\modules\kinerja\models;

use Yii;


/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $password
 * @property string $nama
 * @property integer $jabatan_id
 * @property string $gender
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $alamat
 * @property string $no_telp
 * @property string $email
 * @property string $foto
 * @property string $nip
 * @property integer $atasan_id
 * @property integer $unit_kerja
 * @property integer $jabatan_struktural
 * @property integer $rekan_id
 * @property string $grade
 * @property string $created_date
 * @property integer $super_user
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    private $_jam_absensi;
    private $_menit_telat;
    private $_persen_potongan;
    private $_jumlah_tunjangan_kinerja;
    private $_jumlah_tunjangan_absensi;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade'],'integer'],
            [['no_id_absensi'], 'string', 'max' => 200],
            [['no_id_absensi'], 'unique', 'targetAttribute'=>['no_id_absensi','unit_kerja']]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'nama' => 'Nama',
            'jabatan_id' => 'Jabatan ID',
            'gender' => 'Gender',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat' => 'Alamat',
            'no_telp' => 'No Telp',
            'email' => 'Email',
            'foto' => 'Foto',
            'nip' => 'NIP',
            'atasan_id' => 'Atasan ID',
            'unit_kerja' => 'Perangkat Daerah',
            'jabatan_struktural' => 'Jabatan Struktural',
            'rekan_id' => 'Rekan ID',
            'grade' => 'Grade',
            'created_date' => 'Created Date',
            'super_user' => 'Super User',
        ];
    }

    public function findAllKegiatanBulananBreakdown()
    {
        $query = \app\modules\kinerja\models\KegiatanBulananBreakdown::find();
        $query->andWhere(['kegiatanTahunan.pns_id'=>$this->id]);

        return $query->all();
    }

    public function findAllKegiatanTahunanDetil()
    {
        $query = \app\modules\kinerja\models\KegiatanTahunanDetil::find();
        $query->joinWith(['kegiatanTahunan']);
        $query->andWhere(['kegiatan_tahunan.pns_id'=>$this->id]);
        $query->andWhere(['bulan'=>1]);
        //$query->andWhere(['bulan'=>\app\models\User::getBulan()]);

        return $query->all();
    }

    public function findAbsensiUser()
    {
        $model = \app\modules\absensi\models\User::findOne(['nip'=>$this->nip]);

        if($model===null)
        {
            $model = new \app\modules\absensi\models\User;
            $model->nip = $this->nip;
            $model->username = $this->nip;
            $model->password =  Yii::$app->getSecurity()->generatePasswordHash($this->nip);
            $model->save();
        }

        return $model;

    }

    public function getTunjanganKinerja()
    {
        $tunjangan = $this->findTunjanganKinerja();
        $this->_jumlah_tunjangan_kinerja = $tunjangan->jumlah_tunjangan;

        return $this->_jumlah_tunjangan_kinerja;
    }

    public function getTunjanganAbsensi()
    {
        $tunjangan = $this->findTunjanganAbsensi();
        $this->_jumlah_tunjangan_absensi = $tunjangan->jumlah_tunjangan;

        return $this->_jumlah_tunjangan_absensi;
    }

    public function getTunjanganTotal()
    {
        return $this->_jumlah_tunjangan_kinerja + $this->_jumlah_tunjangan_absensi;
    }

    public function getUnitKerja()
    {
        return null;
    }

    public function queryAbsensi($params=[])
    {
        $query = \app\modules\absensi\models\Absensi::find();
        $query->andWhere(['kode_pegawai'=>$this->no_id_absensi]);

        if(!empty($params['tahun']))
        {
            $tahun = $params['tahun'];
            $bulan_awal = 1;
            $bulan_akhir = 12;

            if(!empty($params['bulan']))
            {
                $bulan_awal =$params['bulan'];
                $bulan_akhir =$params['bulan'];
            }

            $query->andWhere('tanggal_absensi >= :tanggal_absensi_awal AND tanggal_absensi <= :tanggal_absensi_akhir',[
                ':tanggal_absensi_awal'=>$tahun.'-'.$bulan_awal.'-01',
                ':tanggal_absensi_akhir'=>$tahun.'-'.$bulan_akhir.'-31'
            ]);
        }

        if(!empty($params['tanggal']))
        {
            $tanggal = $params['tanggal'];

            $query->andWhere('tanggal_absensi = :tanggal_absensi',[
                ':tanggal_absensi'=>$tanggal
            ]);
        }

        return $query;
    }

    public function queryKeterangan($params=[])
    {
        $query = \app\modules\absensi\models\Keterangan::find();
        $query->andWhere(['nip'=>$this->nip]);

        if(!empty($params['tahun']))
        {
            $tahun = $params['tahun'];
            $bulan_awal = 1;
            $bulan_akhir = 12;

            if(!empty($params['bulan']))
            {
                $bulan_awal =$params['bulan'];
                $bulan_akhir =$params['bulan'];
            }

            $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
                ':tanggal_awal'=>$tahun.'-'.$bulan_awal.'-01',
                ':tanggal_akhir'=>$tahun.'-'.$bulan_akhir.'-31'
            ]);
        }

        if(!empty($params['tanggal']))
        {
            $tanggal = $params['tanggal'];

            $query->andWhere('tanggal = :tanggal',[
                ':tanggal'=>$tanggal
            ]);
        }

        if(!empty($params['jenis']))
        {
            $jenis = $params['jenis'];

            $query->andWhere('id_keterangan_jenis = :jenis',[
                ':jenis'=>$jenis
            ]);
        }

        return $query;
    }

    public function findAllAbsensi($params=[])
    {

        $query = $this->queryAbsensi($params);
        $query->orderBy(['jam_absensi'=>SORT_ASC]);

        return $query->all();
    }

    public function findOneAbsensi($params=[])
    {
        $query = $this->queryAbsensi($params);
        $query->orderBy(['jam_absensi'=>SORT_ASC]);

        return $query->all();
    }

    public function getJumlahHadir($params=[])
    {
        $query = $this->queryAbsensi($params);

        $query->groupBy(['tanggal_absensi']);

        return $query->count();

    }

    public function getJumlahAbsensi($params=[])
    {
        $query = $this->queryAbsensi($params);

        return $query->count();

    }

    public function getJumlahKeterangan($params=[])
    {
        $query = $this->queryKeterangan($params);

        return $query->count();

    }

    public function getJumlahDinasLuar($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::DINAS_LUAR;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahSakit($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::SAKIT;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahIzin($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::IZIN;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getJumlahCuti($params)
    {
        $params['jenis'] = \app\modules\absensi\models\KeteranganJenis::CUTI;

        $query = $this->queryKeterangan($params);

        return $query->count();
    }

    public function getPokokTunjangan()
    {
        $model = \app\modules\kinerja\models\GradeTunjangan::findOne(['id'=>$this->grade]);

        if($model!==null)
            return $model->tunjangan;
        else
            return 0;

    }

    public function findTunjangan($jenis,$params=[])
    {
        $bulan = \app\models\User::getBulan();
        $tahun = \app\models\User::getTahun();

        if(!empty($params['bulan']))
            $bulan = $params['bulan'];

        if(!empty($params['tahun']))
            $bulan = $params['tahun'];

        $query = \app\modules\kinerja\models\UserTunjangan::find();

        $query->andWhere([
            'nip'=>$this->nip,
            'bulan'=>$bulan,
            'tahun'=>$tahun,
            'jenis'=>$jenis
        ]);

        $model = $query->one();

        if($model===null)
        {
            $model = new \app\modules\kinerja\models\UserTunjangan;
            $model->nip = $this->nip;
            $model->bulan = $bulan;
            $model->tahun = $tahun;
            $model->jenis = $jenis;

            $model->save();
        }

        return $model;
    }

    public function findTunjanganKinerja($params=[])
    {
        return $this->findTunjangan('kinerja',$params);
    }

    public function findTunjanganAbsensi($params=[])
    {
        return $this->findTunjangan('absensi',$params);
    }

    public function findAllJamKerja($tanggal)
    {
        $dateTime = date_create($tanggal);

        $query = \app\modules\absensi\models\JamKerja::find();
        $query->andWhere(['hari'=>$dateTime->format('N')]);

        return $query->all();
    }

    public function getJamAbsensi($tanggal,$jamKerja)
    {
        $query = $this->queryAbsensi(['tanggal'=>$tanggal]);
        $query->andWhere('jam_absensi >= :jam_mulai_pindai AND jam_absensi <= :jam_selesai_pindai',[
            ':jam_mulai_pindai'=>$jamKerja->jam_mulai_pindai,
            ':jam_selesai_pindai'=>$jamKerja->jam_selesai_pindai
        ]);

        $model = $query->one();

        $this->_jam_absensi = null;

        if($model!==null)
            $this->_jam_absensi = $model->jam_absensi;

        return $this->_jam_absensi;

    }

    public function getMenitTelat($tanggal,$jamKerja)
    {
        $jamAbsensi = $this->_jam_absensi;

        if($jamAbsensi==null)
        {
            $this->_menit_telat = 15;

            return $this->_menit_telat;

            /*
            if($jamKerja->jenis == 1)
                $jamAbsensi = $jamKerja->jam_selesai_pindai;

            if($jamKerja->jenis == 2)
                $jamAbsensi = $jamKerja->jam_mulai_pindai;
            */
        }

        if($jamAbsensi >= $jamKerja->jam_mulai_normal AND $jamAbsensi <= $jamKerja->jam_selesai_normal)
        {
            $this->_menit_telat = 0;

            return $this->_menit_telat;

        } else {

            if($jamAbsensi < $jamKerja->jam_mulai_normal)
                $interval = date_diff(date_create($jamKerja->jam_mulai_normal),date_create($jamAbsensi));

            if($jamAbsensi > $jamKerja->jam_selesai_normal)
                $interval = date_diff(date_create($jamKerja->jam_selesai_normal),date_create($jamAbsensi));

            //$interval = date_diff(date_create("09:30:00"),date_create("09:11:00"));

            $this->_menit_telat = $interval->format('%i');

            return $this->_menit_telat;
        }

    }

    public function getPersenPotongan($tanggal,$jamKerja)
    {
        $menitTelat = $this->_menit_telat;

        if($menitTelat > 15)
            return 45;

        else
            return $menitTelat*3;
    }

    public function findAbsensiRekap($params=[])
    {
        $bulan = \app\models\User::getBulan();
        $tahun = \app\models\User::getTahun();

        if(!empty($params['bulan']))
            $bulan = $params['bulan'];

        if(!empty($params['tahun']))
            $bulan = $params['tahun'];

        $query = \app\modules\absensi\models\Rekap::find();

        $query->andWhere([
            'nip'=>$this->nip,
            'bulan'=>$bulan,
            'tahun'=>$tahun,
        ]);

        $model = $query->one();

        if($model===null)
        {
            $model = new \app\modules\absensi\models\Rekap;
            $model->nip = $this->nip;
            $model->bulan = $bulan;
            $model->tahun = $tahun;

            $model->save();

        }

        return $model;
    }
}

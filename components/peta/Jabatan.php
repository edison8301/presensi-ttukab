<?php

namespace app\components\peta;

use yii\base\BaseObject;
use yii\base\Model;

/**
 * Class Jabatan untuk instance dari OrgPeta
 */
class Jabatan extends Model
{
    const STRUKTURAL = "Struktural";
    const NON_STRUKTURAL = "Non-Struktural";

    /**
     * @var model object AnjabJabatan
     */
    public $model = null;

    public $detail = false;

    public $level;

    /**
     * @var int primary key jabatan
     */
    public $id;

    /**
     * @var string nama jabatan
     */
    public $nama;

    /**
     * @var bool
     */
    public $status_sekretaris = false;

    /**
     * @var bool
     */
    public $status_sekretariat = false;

    /**
     * @var bool
     */
    public $status_kepala = false;

    /**
     * @var bool
     */
    public $status_fungsional = false;


    /**
     * @var bool
     */
    public $status_view = false;

    /**
     * @var int
     */
    public $id_induk;
    /**
     * @var null | array sub / anak jabatan dari jabatan terkait
     */
    public $anak;

    public $anakFungsional;

    /**
     * @var Jabatan[]
     */
    public $sub;

    /**
     * @var Jabatan[]
     */
    public $subStruktural;

    /**
     * @var Jabatan[]
     */
    public $subNonStruktural;

    /**
     * @var string Jenis Jabatan
     */
    public $jenis;

    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var Jabatan
     */
    public $induk;

    /**
     * @var int
     */
    private $_lebar;

    /**
     * @var int
     */
    public $kelas_jabatan;

    public $jumlah_pegawai;

    public $jumlah_pegawai_abk;

    public function __construct($params=[])
    {
        $this->id = @$params['id'];
        $this->model = @$params['model'];
        $this->nama = @$params['nama'];
        $this->id_induk = @$params['id_induk'];
        $this->status_kepala = @$params['status_kepala'];
        $this->status_fungsional = @$params['status_fungsional'];
        $this->status_sekretaris = @$params['status_sekretaris'];
        $this->status_sekretariat = @$params['status_sekretariat'];
        $this->anak = @$params['anak'];
        $this->anakFungsional = @$params['anakFungsional'];
        $this->jenis = @$params['jenis'];

        //parent::__construct($config);
        if ($this->model === null) {
            //$this->model = AnjabJabatan::findOne($this->id);
        }
    }

    public function getIsSekretaris()
    {
        return (bool) $this->status_sekretaris;
    }

    public function getIsSekretariat()
    {
        return (bool) $this->status_sekretariat;
    }

    public function getIsFungsional()
    {
        return (bool) $this->status_fungsional;
    }

    public function getIsKepala()
    {
        return (bool) $this->status_kepala;
    }

    /**
     * @return bool
     */
    public function getIsStruktural()
    {
        return $this->jenis=="Struktural";
    }

    public function getHasInduk()
    {
        return $this->id_induk !== null;
    }

    public function getHasAnak()
    {
        return !empty($this->anak);
    }

    public function getKelasJabatan()
    {
        return $this->model->findEvjabFaktor()->kelas_jabatan;
    }

    public function getHasSub()
    {
        if(count($this->sub)==0) {
            return false;
        }

        return true;
    }

    public function getLebar()
    {
        if($this->_lebar==null) {
            $this->setLebar();
        }

        return $this->_lebar;
    }

    public function setLebar()
    {

    }

}

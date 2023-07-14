<?php

namespace app\models;

use app\commands\KirimKeMesinCommand;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Kirim ke mesin form
 *
 * @property InstansiPegawaiQuery $querySearch
 * @property-read InstansiPegawaiSearch $instansiPegawaiSearch
 */
class KirimKeMesinForm extends Model
{
    /**
     * @var string
     */
    public $nama;
    /**
     * @var string
     */
    public $kode_presensi;
    /**
     * @var string
     */
    public $nip;
    /**
     * @var int
     */
    public $id_instansi;
    /**
     * @var int
     */
    public $status_honorer;
    /**
     * @var int
     */
    public $id_pegawai_jenis;
    /**
     * @var int
     */
    public $SN_id;
    /**
     * @var int
     */
    public $proses;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SN_id'], 'required'],
            [['nama', 'kode_presensi', 'nip','SN_id'], 'string'],
            [['id_instansi', 'status_honorer', 'proses', 'id_pegawai_jenis'], 'integer'],
        ];
    }

    /**
     * @var InstansiPegawaiSearch
     */
    protected $_instansiPegawaiSearch;

    /**
     * @return InstansiPegawaiSearch
     */
    public function getInstansiPegawaiSearch()
    {
        if ($this->_instansiPegawaiSearch === null) {
            $this->_instansiPegawaiSearch = new InstansiPegawaiSearch();
        }
        $this->setInstansiPegawaiSearchAttributes();
        return $this->_instansiPegawaiSearch;
    }

    /**
     * @return void
     */
    private function setInstansiPegawaiSearchAttributes()
    {
        $this->_instansiPegawaiSearch->setAttributes([
            'bulan' => date('m'),
            'nama_pegawai' => $this->nama,
            'nip_pegawai' => $this->nip,
            'id_instansi' => $this->id_instansi,
            'status_honorer' => $this->status_honorer,
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id_pegawai_jenis' => 'Jenis Pegawai',
            'id_instansi' => 'Instansi',
            'SN_id' => 'Mesin Absensi'
        ];
    }

    /**
     * @return InstansiPegawaiQuery
     */
    public function getQuerySearch()
    {
        return $this->getInstansiPegawaiSearch()->getQuerySearch([]);
    }

    public function kirimKeMesin()
    {
        $allPegawai = array_map(
                function (InstansiPegawai $instansiPegawai) {
                    return $instansiPegawai->pegawai;
                },
                $this->querySearch->all()
            );
        $runner = new KirimKeMesinCommand(['pegawai' => $allPegawai, 'SN_id' => $this->SN_id]);
        $runner->run();
    }
}

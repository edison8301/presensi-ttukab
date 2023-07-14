<?php

namespace app\models;

use Yii;
use app\components\Helper;
use app\components\Session;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use function in_array;

/**
 * InstansiPegawaiSearch represents the model behind the search form of `app\models\InstansiPegawai`.
 */
class InstansiPegawaiSearch extends InstansiPegawai
{

    public $id_jabatan_induk;
    public $bulan;
    public $tahun;
    public $nama_pegawai;
    public $manyIdInstansi;
    public $nip_pegawai;
    public $jenis = 'tpp';

    public $exportJenis;
    public $exportDari;

    public $_query;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_pegawai', 'id_jabatan', 'status_hapus',
                'id_jabatan_atasan','id_jabatan_induk','bulan'
            ], 'integer'],
            [['tahun', 'nama_jabatan', 'tanggal_berlaku','tanggal_selesai',
                'waktu_dihapus','tanggal_mulai','nama_pegawai','manyIdInstansi','nip_pegawai'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return InstansiPegawaiQuery
     */

    public function getQuerySearch($params=null)
    {
        $this->load($params);

        $query = InstansiPegawai::find();
        $query->joinWith(['pegawai','jabatan']);
        $query->orderBy([
            'id_instansi' => SORT_ASC,
            'jabatan.id_eselon' => SORT_ASC
        ]);

        $query->andWhere('pegawai.id IS NOT NULL');

        $tahun = $this->tahun;

        if($tahun === null) {
            $tahun = Session::getTahun();
        }

        if($this->bulan!=null) {
            $date = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$this->bulan.'-01');
            $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal',[
               ':tanggal' => $date->format('Y-m-15')
            ]);
        }

        if (php_sapi_name() !== 'cli') {
            if (User::isInstansi() || User::isAdminInstansi() || User::isOperatorAbsen()) {
                $this->manyIdInstansi = User::getListIdInstansi();
            }

            if (!empty($this->manyIdInstansi)) {
                $query->andWhere(['instansi_pegawai.id_instansi' => $this->manyIdInstansi]);
            }

            if (User::isAdminInstansi()
                && $this->id_instansi !== null
                && in_array($this->id_instansi, User::getListIdInstansi(), false)) {
                $query->andWhere(['instansi_pegawai.id_instansi' => $this->id_instansi]);
            }

            if (User::isAdmin() && $this->id_instansi !== null
                OR Session::isPemeriksaAbsensi() && $this->id_instansi !== null
                OR Session::isPemeriksaKinerja() && $this->id_instansi !== null
            ) {
                $query->andFilterWhere(['instansi_pegawai.id_instansi' => $this->id_instansi]);
            }

            if($this->id_pegawai!=null) {
                $this->nama_pegawai = @$this->pegawai->nama;
            }
        }

        if ($this->jenis == 'penundaan-tpp') {
            $date = \DateTime::createFromFormat('Y-n-d',$tahun.'-'.$this->bulan.'-01');
            $query->joinWith('manyPegawaiTundaBayar');
            $query->andWhere('pegawai_tunda_bayar.tanggal_mulai <= :tanggal AND pegawai_tunda_bayar.tanggal_selesai >= :tanggal', [
               ':tanggal_mulai' => $date->format('Y-m-15'),
            ]);
        }

        $query->andFilterWhere([
            'instansi_pegawai.id' => $this->id,
            'instansi_pegawai.id_pegawai' => $this->id_pegawai,
            'instansi_pegawai.id_jabatan' => $this->id_jabatan,
            'instansi_pegawai.status_hapus' => $this->status_hapus
        ]);

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        $query->andFilterWhere(['like', 'pegawai.nip', $this->nip_pegawai]);
        $query->andFilterWhere(['like', 'instansi_pegawai.nama_jabatan', $this->nama_jabatan]);
        $query->andFilterWhere(['like', 'instansi_pegawai.tanggal_berlaku', $this->tanggal_berlaku]);
        $query->andFilterWhere(['like', 'instansi_pegawai.tanggal_mulai', $this->tanggal_mulai]);
        $query->andFilterWhere(['like', 'instansi_pegawai.tanggal_selesai', $this->tanggal_selesai]);

        $this->_query = $query;
        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);
        /* $query->andWhere([
            'instansi_pegawai.status_plt' => 0
        ]); */
        $query->groupBy(['pegawai.nip']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*'sort' => [
                'defaultOrder' => [
                    'jabatan.id_eselon' => SORT_ASC
                ]
            ]*/
        ]);

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $query = $this->getQuerySearch($params);

        $query->filterByBulanTahun();

        $query->joinWith(['jabatan']);
        $query->andWhere(['jabatan.id_induk'=>User::getIdJabatanBerlaku()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function getBulanLengkapTahun()
    {
        return Helper::getBulanLengkap($this->bulan).' '.User::getTahun();
    }

    public function isFiltered()
    {
        return $this->bulan !== null && $this->instansi !== null;
    }

    /**
     * @return InstansiPegawai[]
     */
    public function getAll()
    {
        $query = $this->getQuerySearch();
        $query->groupBy([
            'pegawai.nip',
        ]);
        return $query->all();
    }

    public function getJumlah($params=[])
    {
        if ($this->_query === null) {
            $this->getQuerySearch($params);
        }

        $query = $this->_query;
        return $query->count();
    }

    public function getListItemPage($params=[])
    {
        if ($this->_query === null) {
            $this->getQuerySearch($params);
        }

        $query = $this->_query;

        $jumlah = $query->count();
        $bagian = floor($jumlah / 100);
        $sisa = $jumlah - ($bagian*100);

        $list = [];
        $list[null] = 'Semua';

        if ($bagian <= 1) {
            return $list;
        }

        for ($i=1; $i<=$bagian; $i++) {
            $offset = ($i-1) * 100;
            $limit = $i * 100;

            $displayOffset = $i == 1 ? 1 : $offset + 1;
            $displayLimit = $limit;
            $list["&offset=$offset&limit=100"] = "$displayOffset - $displayLimit";
        }

        if ($sisa != 0) {
            $offset = $bagian * 100;
            $limit = $offset + $sisa;
            $list["&offset=$offset&limit=100"] = ($offset+1) . ' - '. $limit;
        }

        return $list;
    }
}

<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Pegawai;

/**
 * KegiatanBulananSearch represents the model behind the search form of `app\models\KegiatanBulanan`.
 */
class KegiatanBulananSearch extends KegiatanBulanan
{
    /**
     * @inheritdoc
     */

    public $mode = 'pegawai';
    public $id_pegawai;
    public $tahun;
    public $nomor_skp;
    public $nama_kegiatan;

    public $id_kegiatan_tahunan_versi;

    public function rules()
    {
        return [
            [['id','id_pegawai', 'id_kegiatan_tahunan', 'bulan', 'target', 'tahun'], 'integer'],
            [['nomor_skp','nama_kegiatan'],'string'],
            [['id_kegiatan_tahunan_versi'], 'integer'],
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
     * @return KegiatanBulananQuery
     */
    public function querySearch($params, $subordinat = false)
    {
        $this->load($params);

        if (empty($this->tahun)) {
            $this->tahun = User::getTahun();
        }

        if(User::isPegawai() AND $this->mode=='pegawai') {
            $this->id_pegawai = User::getIdPegawai();
        }

        if($this->bulan == null) {
            $this->bulan = date('n');
        }

        $query = KegiatanBulanan::find();

        $query->joinWith(['kegiatanTahunan','instansiPegawai','instansiPegawaiSkp']);

        $query->andWhere(['kegiatan_tahunan.tahun' => $this->tahun]);
        $query->andWhere(['kegiatan_tahunan.status_hapus' => 0]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_status'=>1]);

        $query->andWhere('kegiatan_bulanan.target IS NOT NULL AND kegiatan_bulanan.target != 0');

        $query->andFilterWhere([
            'kegiatan_bulanan.id' => $this->id,
            'kegiatan_tahunan.id_pegawai' => $this->id_pegawai,
            'kegiatan_bulanan.id_kegiatan_tahunan' => $this->id_kegiatan_tahunan,
            'kegiatan_bulanan.bulan' => $this->bulan,
            'kegiatan_bulanan.target' => $this->target,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => $this->id_kegiatan_tahunan_versi
        ]);

        $query->andFilterWhere(['like','instansi_pegawai_skp.nomor',$this->nomor_skp]);
        $query->andFilterWhere(['like','kegiatan_tahunan.nama',$this->nama_kegiatan]);

        return $query;
    }

    public function getQueryBawahan($params)
    {
        $query = $this->querySearch($params);

        $query->joinWith(['instansiPegawai']);
        $query->andWhere(['instansi_pegawai.id_jabatan'=>User::getListIdJabatanBawahan()]);

        return $query;
    }

    public function search($params, $bulan=null, $subordinat = false)
    {
        $query = $this->querySearch($params, $subordinat);

        //$query->bulan($bulan);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $query = $this->getQueryBawahan($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchBySession($params, $bulan)
    {
        $query = $this->querySearch($params);
        $query->byPegawaiSession();
        $query->bulan($bulan);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchSubordinat($params, $bulan)
    {
        $query = $this->getQuerySearch($params);
        $query->subordinat();
        $query->bulan($bulan);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function getListPegawai()
    {
        if($this->mode=='pegawai')
            return Pegawai::getList();

        if($this->mode == 'bawahan')
        {
            $query = Pegawai::find();
            $query->andWhere(['id_atasan'=>User::getIdPegawai()]);
            $query->orderBy(['nama'=>SORT_ASC]);

            return ArrayHelper::map($query->all(),'id','nama');
        }
    }

}

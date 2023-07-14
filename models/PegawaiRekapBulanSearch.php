<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiRekapBulan;

/**
 * PegawaiRekapBulanSearch represents the model behind the search form of `app\models\PegawaiRekapBulan`.
 */
class PegawaiRekapBulanSearch extends PegawaiRekapBulan
{
    public $nama_pegawai;
    public $nip_pegawai;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'bulan', 'id_pegawai_rekap_jenis', 'status_kunci'], 'integer'],
            [['tahun', 'nilai', 'keterangan', 'waktu_kunci', 'waktu_buat'], 'safe'],
            [['nama_pegawai'], 'safe'],
            [['nip_pegawai'], 'safe']
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
     * @return PegawaiRekapBulanQuery
     */

    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = PegawaiRekapBulan::find();

        $query->joinWith(['pegawai']);
        $query->with(['pegawai','pegawaiRekapJenis']);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'id_pegawai_rekap_jenis' => $this->id_pegawai_rekap_jenis,
            'nilai' => $this->nilai,
            'keterangan' => $this->keterangan,
            'status_kunci' => $this->status_kunci,
            'waktu_kunci' => $this->waktu_kunci,
            'waktu_buat' => $this->waktu_buat,
        ]);

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        $query->andFilterWhere(['like', 'pegawai.nip', $this->nip_pegawai]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KegiatanRealisasi;

/**
 * KegiatanRealisasiSearch represents the model behind the search form of `app\models\KegiatanRealisasi`.
 */
class KegiatanRealisasiSearch extends KegiatanRealisasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kuantitas'], 'integer'],
            [['tahun', 'kode_instansi', 'kode_pegawai', 'kode_kegiatan', 'kode_kegiatan_realisasi', 'tanggal', 'uraian', 'jam_mulai', 'jam_selesai', 'berkas', 'kode_kegiatan_realisasi_status', 'waktu_disetujui', 'kode_pegawai_penyetuju'], 'safe'],
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
     * @return ActiveDataProvider
     */

    public function getQuerySearch($params)
    {
        $query = KegiatanRealisasi::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tahun' => $this->tahun,
            'tanggal' => $this->tanggal,
            'kuantitas' => $this->kuantitas,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'waktu_disetujui' => $this->waktu_disetujui,
        ]);

        $query->andFilterWhere(['like', 'kode_instansi', $this->kode_instansi])
            ->andFilterWhere(['like', 'kode_pegawai', $this->kode_pegawai])
            ->andFilterWhere(['like', 'kode_kegiatan', $this->kode_kegiatan])
            ->andFilterWhere(['like', 'kode_kegiatan_realisasi', $this->kode_kegiatan_realisasi])
            ->andFilterWhere(['like', 'uraian', $this->uraian])
            ->andFilterWhere(['like', 'berkas', $this->berkas])
            ->andFilterWhere(['like', 'kode_kegiatan_realisasi_status', $this->kode_kegiatan_realisasi_status])
            ->andFilterWhere(['like', 'kode_pegawai_penyetuju', $this->kode_pegawai_penyetuju]);

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

    public function searchByPegawai($params)
    {
        $params['KegiatanRealisasiSearch']['kode_pegawai'] = Yii::$app->user->identity->kode_pegawai;
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KegiatanHarianRealisasi;

/**
 * KegiatanHarianRealisasiSearch represents the model behind the search form of `app\models\KegiatanHarianRealisasi`.
 */
class KegiatanHarianRealisasiSearch extends KegiatanHarianRealisasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_kegiatan_harian', 'kuantitas', 'id_pegawai_penyetuju'], 'integer'],
            [['tanggal', 'uraian', 'jam_mulai', 'jam_selesai', 'berkas', 'kode_kegiatan_realisasi_status', 'waktu_disetujui'], 'safe'],
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
        $query = KegiatanHarianRealisasi::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_kegiatan_harian' => $this->id_kegiatan_harian,
            'tanggal' => $this->tanggal,
            'kuantitas' => $this->kuantitas,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'id_pegawai_penyetuju' => $this->id_pegawai_penyetuju,
            'waktu_disetujui' => $this->waktu_disetujui,
        ]);

        $query->andFilterWhere(['like', 'uraian', $this->uraian])
            ->andFilterWhere(['like', 'berkas', $this->berkas])
            ->andFilterWhere(['like', 'kode_kegiatan_realisasi_status', $this->kode_kegiatan_realisasi_status]);

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

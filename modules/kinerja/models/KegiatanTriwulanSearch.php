<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\KegiatanTriwulan;

/**
 * modelsKegiatanTriwulanSearch represents the model behind the search form of `app\modules\kinerja\models\KegiatanTriwulan`.
 */
class KegiatanTriwulanSearch extends KegiatanTriwulan
{
    const SCENARIO_PEGAWAI = 'pegawai';
    const SCENARIO_BAWAHAN = 'bawahan';
    const SCENARIO_ATASAN =  'atasan';
    
    public $mode = 'pegawai';
    public $nomor_skp;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_kegiatan_tahunan', 'id_kegiatan_bulanan', 'periode','id_pegawai'], 'integer'],
            [['tahun', 'target', 'realisasi', 'deskripsi_capaian', 'kendala', 'tindak_lanjut'], 'safe'],
            [['persen_capaian'], 'number'],
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
        $query = KegiatanTriwulan::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_kegiatan_tahunan' => $this->id_kegiatan_tahunan,
            'id_kegiatan_bulanan' => $this->id_kegiatan_bulanan,
            'tahun' => $this->tahun,
            'periode' => $this->periode,
            'persen_capaian' => $this->persen_capaian,
        ]);

        $query->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'realisasi', $this->realisasi])
            ->andFilterWhere(['like', 'deskripsi_capaian', $this->deskripsi_capaian])
            ->andFilterWhere(['like', 'kendala', $this->kendala])
            ->andFilterWhere(['like', 'tindak_lanjut', $this->tindak_lanjut]);

        return $query;
    }
    
    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        return $dataProvider;
    }


}

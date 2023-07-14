<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\JamKerjaReguler;

/**
 * JamKerjaRegulerSearch represents the model behind the search form of `app\modules\absensi\models\JamKerjaReguler`.
 */
class JamKerjaRegulerSearch extends JamKerjaReguler
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_shift_kerja_reguler', 'id_jam_kerja_jenis', 'hari'], 'integer'],
            [['nama', 'jam_mulai_hitung', 'jam_selesai_hitung', 'jam_minimal_absensi', 'jam_maksimal_absensi'], 'safe'],
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
        $query = JamKerjaReguler::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_shift_kerja_reguler' => $this->id_shift_kerja_reguler,
            'id_jam_kerja_jenis' => $this->id_jam_kerja_jenis,
            'hari' => $this->hari,
            'jam_mulai_hitung' => $this->jam_mulai_hitung,
            'jam_selesai_hitung' => $this->jam_selesai_hitung,
            'jam_minimal_absensi' => $this->jam_minimal_absensi,
            'jam_maksimal_absensi' => $this->jam_maksimal_absensi,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

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

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TunjanganInstansiJenisJabatanKelas;

/**
 * TunjanganUnitJenisJabatanKelasSearch represents the model behind the search form of `app\models\TunjanganUnitJenisJabatanKelas`.
 */
class TunjanganInstansiJenisJabatanKelasSearch extends TunjanganInstansiJenisJabatanKelas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_jenis_jabatan', 'kelas_jabatan'], 'integer'],
            [['nilai_tpp'], 'number'],
            [['kategori'], 'integer'],
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
     * @return \yii\db\ActiveQuery
     */

    public function getQuerySearch($params)
    {
        $query = TunjanganInstansiJenisJabatanKelas::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'id_jenis_jabatan' => $this->id_jenis_jabatan,
            'kelas_jabatan' => $this->kelas_jabatan,
            'nilai_tpp' => $this->nilai_tpp,
            'kategori' => $this->kategori,
        ]);

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

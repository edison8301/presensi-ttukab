<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\JabatanTunjanganFungsional;

/**
 * JabatanTunjanganFungsionalSearch represents the model behind the search form of `app\modules\tunjangan\models\JabatanTunjanganFungsional`.
 */
class JabatanTunjanganFungsionalSearch extends JabatanTunjanganFungsional
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_tingkatan_fungsional'], 'integer'],
            [['besaran_tpp'], 'number'],
            [['kelas_jabatan', 'status_p3k'], 'integer'],
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
        $query = JabatanTunjanganFungsional::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'id_tingkatan_fungsional' => $this->id_tingkatan_fungsional,
            'besaran_tpp' => $this->besaran_tpp,
            'kelas_jabatan' => $this->kelas_jabatan,
            'status_p3k' => $this->status_p3k,
        ]);

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

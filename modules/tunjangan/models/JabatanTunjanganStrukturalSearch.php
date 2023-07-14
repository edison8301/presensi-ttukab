<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\JabatanTunjanganStruktural;

/**
 * JabatanTunjanganStrukturalSearch represents the model behind the search form of `app\modules\tunjangan\models\JabatanTunjanganStruktural`.
 */
class JabatanTunjanganStrukturalSearch extends JabatanTunjanganStruktural
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_eselon'], 'integer'],
            [['besaran_tpp'], 'number'],
            [['id_golongan'],'safe'],
            [['id_jabatan_tunjangan_golongan', 'kelas_jabatan'], 'integer'],
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
        $query = JabatanTunjanganStruktural::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'id_eselon' => $this->id_eselon,
            'besaran_tpp' => $this->besaran_tpp,
            'id_golongan' => $this->id_golongan,
            'id_jabatan_tunjangan_golongan' => $this->id_jabatan_tunjangan_golongan,
            'kelas_jabatan' => $this->kelas_jabatan,
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

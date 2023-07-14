<?php

namespace app\modules\tandatangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tandatangan\models\LogSigning;

/**
 * LogSigningSearch represents the model behind the search form of `app\modules\tandatangan\models\LogSigning`.
 */
class LogSigningSearch extends LogSigning
{
    public $nama_berkas;
    public $id_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_berkas'], 'integer'],
            [['nama', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_berkas', 'id_instansi'], 'safe'],
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
        $query = LogSigning::find();

        $this->load($params);

        // add conditions that should always apply here
        $query->joinWith(['berkas']);
        $query->andFilterWhere(['like', 'berkas.nama', $this->nama_berkas]);
        $query->andFilterWhere(['berkas.id_instansi' => $this->id_instansi]);

        // grid filtering conditions
        $query->andFilterWhere([
            'log_signing.id' => $this->id,
            'log_signing.id_berkas' => $this->id_berkas,
            'log_signing.created_at' => $this->created_at,
            'log_signing.updated_at' => $this->updated_at,
            'log_signing.deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'log_signing.nama', $this->nama]);

        return $query;
    }
    
    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);        

        return $dataProvider;
    }


}

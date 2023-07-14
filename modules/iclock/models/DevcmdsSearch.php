<?php

namespace app\modules\iclock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\iclock\models\Devcmds;

/**
 * DevcmdsSearch represents the model behind the search form of `app\modules\iclock\models\Devcmds`.
 */
class DevcmdsSearch extends Devcmds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'CmdReturn', 'User_id'], 'integer'],
            [['SN_id', 'CmdContent', 'CmdCommitTime', 'CmdTransTime', 'CmdOverTime'], 'safe'],
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
        $query = Devcmds::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'CmdCommitTime' => $this->CmdCommitTime,
            'CmdTransTime' => $this->CmdTransTime,
            'CmdOverTime' => $this->CmdOverTime,
            'CmdReturn' => $this->CmdReturn,
            'User_id' => $this->User_id,
        ]);

        $query->andFilterWhere(['like', 'SN_id', $this->SN_id])
            ->andFilterWhere(['like', 'CmdContent', $this->CmdContent])->orderBy(['CmdCommitTime' => SORT_DESC]);

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

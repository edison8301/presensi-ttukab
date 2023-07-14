<?php

namespace app\modules\iclock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\iclock\models\Template;

/**
 * TemplateSearch represents the model behind the search form of `app\models\Template`.
 */
class TemplateSearch extends Template
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['templateid', 'userid', 'FingerID', 'Valid', 'DelTag', 'USETYPE'], 'integer'],
            [['Template', 'SN', 'UTime', 'BITMAPPICTURE', 'BITMAPPICTURE2', 'BITMAPPICTURE3',
                'BITMAPPICTURE4', 'Template2', 'Template3','userinfo_name','userinfo_badgenumber'], 'safe'],
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
        $query = Template::find();
        $query->joinWith(['userinfo']);

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'templateid' => $this->templateid,
            'template.userid' => $this->userid,
            'FingerID' => $this->FingerID,
            'Valid' => $this->Valid,
            'DelTag' => $this->DelTag,
            'UTime' => $this->UTime,
            'USETYPE' => $this->USETYPE,
        ]);

        $query->andFilterWhere(['like', 'Template', $this->Template])
            ->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'userinfo.name', $this->userinfo_name])
            ->andFilterWhere(['like', 'userinfo.badgenumber', $this->userinfo_badgenumber])
            ->andFilterWhere(['like', 'BITMAPPICTURE', $this->BITMAPPICTURE])
            ->andFilterWhere(['like', 'BITMAPPICTURE2', $this->BITMAPPICTURE2])
            ->andFilterWhere(['like', 'BITMAPPICTURE3', $this->BITMAPPICTURE3])
            ->andFilterWhere(['like', 'BITMAPPICTURE4', $this->BITMAPPICTURE4])
            ->andFilterWhere(['like', 'Template2', $this->Template2])
            ->andFilterWhere(['like', 'Template3', $this->Template3]);

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

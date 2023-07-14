<?php

namespace app\modules\iclock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\iclock\models\Checkinout;

/**
 * CheckinoutSearch represents the model behind the search form about `app\models\Checkinout`.
 */
class CheckinoutSearch extends Checkinout
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'verifycode'], 'integer'],
            [['checktime', 'checktype','userinfo_badgenumber', 'SN', 'sensorid', 'WorkCode', 'Reserved'], 'safe'],
            [['nama_pegawai'],'safe']
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
    public function search($params)
    {
        $this->load($params);
        $query = Checkinout::find();
        $query->joinWith(['userinfo']);

        if($this->nama_pegawai!=null) {
            $query->joinWith(['pegawai']);    
            $query->andFilterWhere(['like', 'tunjangan.pegawai.nama', $this->nama_pegawai]);
        }
        
    
        $query->andFilterWhere([
            'id' => $this->id,
            'checkinout.userid' => $this->userid,
            'verifycode' => $this->verifycode,
        ]);

        $query->andFilterWhere(['like', 'checktype', $this->checktype])
            ->andFilterWhere(['like', 'checkinout.SN', $this->SN])
            ->andFilterWhere(['like', 'checktime', $this->checktime])
            ->andFilterWhere(['like', 'userinfo.badgenumber', $this->userinfo_badgenumber])
            ->andFilterWhere(['like', 'sensorid', $this->sensorid])
            ->andFilterWhere(['like', 'WorkCode', $this->WorkCode])
            ->andFilterWhere(['like', 'Reserved', $this->Reserved]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }
}

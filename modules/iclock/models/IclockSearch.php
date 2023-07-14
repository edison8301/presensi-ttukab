<?php

namespace app\modules\iclock\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\iclock\models\Iclock;

/**
 * IclockSearch represents the model behind the search form of `app\modules\iclock\models\Iclock`.
 */
class IclockSearch extends Iclock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SN', 'LastActivity', 'TransTimes', 'LogStamp', 'OpLogStamp', 'PhotoStamp', 'Alias', 'UpdateDB', 'Style', 'FWVersion', 'MainTime', 'DeviceName', 'AlgVer', 'FlashSize', 'FreeFlashSize', 'Language', 'VOLUME', 'DtFmt', 'IPAddress', 'IsTFT', 'Platform', 'Brightness', 'BackupDev', 'OEMVendor', 'City', 'FPVersion', 'PushVersion'], 'safe'],
            [['State', 'TransInterval', 'DeptID', 'FPCount', 'TransactionCount', 'UserCount', 'MaxFingerCount', 'MaxAttLogCount', 'AccFun', 'TZAdj', 'DelTag'], 'integer'],
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
        $query = Iclock::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'State' => $this->State,
            'LastActivity' => $this->LastActivity,
            'TransInterval' => $this->TransInterval,
            'DeptID' => $this->DeptID,
            'FPCount' => $this->FPCount,
            'TransactionCount' => $this->TransactionCount,
            'UserCount' => $this->UserCount,
            'MaxFingerCount' => $this->MaxFingerCount,
            'MaxAttLogCount' => $this->MaxAttLogCount,
            'AccFun' => $this->AccFun,
            'TZAdj' => $this->TZAdj,
            'DelTag' => $this->DelTag,
        ]);

        $query->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'TransTimes', $this->TransTimes])
            ->andFilterWhere(['like', 'LogStamp', $this->LogStamp])
            ->andFilterWhere(['like', 'OpLogStamp', $this->OpLogStamp])
            ->andFilterWhere(['like', 'PhotoStamp', $this->PhotoStamp])
            ->andFilterWhere(['like', 'Alias', $this->Alias])
            ->andFilterWhere(['like', 'UpdateDB', $this->UpdateDB])
            ->andFilterWhere(['like', 'Style', $this->Style])
            ->andFilterWhere(['like', 'FWVersion', $this->FWVersion])
            ->andFilterWhere(['like', 'MainTime', $this->MainTime])
            ->andFilterWhere(['like', 'DeviceName', $this->DeviceName])
            ->andFilterWhere(['like', 'AlgVer', $this->AlgVer])
            ->andFilterWhere(['like', 'FlashSize', $this->FlashSize])
            ->andFilterWhere(['like', 'FreeFlashSize', $this->FreeFlashSize])
            ->andFilterWhere(['like', 'Language', $this->Language])
            ->andFilterWhere(['like', 'VOLUME', $this->VOLUME])
            ->andFilterWhere(['like', 'DtFmt', $this->DtFmt])
            ->andFilterWhere(['like', 'IPAddress', $this->IPAddress])
            ->andFilterWhere(['like', 'IsTFT', $this->IsTFT])
            ->andFilterWhere(['like', 'Platform', $this->Platform])
            ->andFilterWhere(['like', 'Brightness', $this->Brightness])
            ->andFilterWhere(['like', 'BackupDev', $this->BackupDev])
            ->andFilterWhere(['like', 'OEMVendor', $this->OEMVendor])
            ->andFilterWhere(['like', 'City', $this->City])
            ->andFilterWhere(['like', 'FPVersion', $this->FPVersion])
            ->andFilterWhere(['like', 'PushVersion', $this->PushVersion]);

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

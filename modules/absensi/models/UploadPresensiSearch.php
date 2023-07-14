<?php

namespace app\modules\absensi\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\UploadPresensi;

/**
 * UploadPresensiSearch represents the model behind the search form of `app\modules\absensi\models\UploadPresensi`.
 */
class UploadPresensiSearch extends UploadPresensi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['SN'], 'safe'],
            [['file', 'user_pengupload', 'waktu_diupload'], 'safe'],
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
        $this->load($params);

        $query = UploadPresensi::find();

        if(Session::isOperatorAbsen() OR Session::isAdminInstansi() OR Session::isInstansi()) {
            $query->andWhere([
                'SN'=>Session::getListSN()
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'waktu_diupload' => $this->waktu_diupload,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'user_pengupload', $this->user_pengupload]);

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

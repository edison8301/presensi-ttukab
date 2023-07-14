<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\UploadPresensiLog;

/**
 * UploadPresensiLogSearch represents the model behind the search form of `app\modules\absensi\models\UploadPresensiLog`.
 */
class UploadPresensiLogSearch extends UploadPresensiLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_upload_presensi', 'checktype', 'status_kirim'], 'integer'],
            [['badgenumber', 'checktime', 'SN'], 'safe'],
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
        $query = UploadPresensiLog::find()
            ->joinWith('pegawai')
            ->with(['pegawai', 'mesinAbsensi.instansi']);

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_upload_presensi' => $this->id_upload_presensi,
            'checktime' => $this->checktime,
            'checktype' => $this->checktype,
            'status_kirim' => $this->status_kirim,
        ]);

        $query->andFilterWhere([
                'or',
                ['like', 'pegawai.nama', $this->badgenumber],
                ['like', 'pegawai.nip', $this->badgenumber]
            ])
            ->andFilterWhere(['like', 'SN', $this->SN]);

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

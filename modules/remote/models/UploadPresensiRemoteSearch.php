<?php

namespace app\modules\remote\models;

use app\models\User;
use app\modules\absensi\models\MesinAbsensi;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\remote\models\UploadPresensiRemote;

/**
 * UploadPresensiRemoteSearch represents the model behind the search form of `app\modules\remote\models\UploadPresensiRemote`.
 */
class UploadPresensiRemoteSearch extends UploadPresensiRemote
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_queue', 'status'], 'integer'],
            [['SN', 'file', 'user_pengupload', 'waktu_diupload'], 'safe'],
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
        $query = UploadPresensiRemote::find();

        $this->load($params);

        // add conditions that should always apply here
        if (User::isAdminInstansi() OR User::isInstansi()) {
            $query->andFilterWhere([
                'SN'=>MesinAbsensi::getListSn(['id_instansi'=>User::getIdInstansi()])
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'upload_presensi_remote.id' => $this->id,
            'upload_presensi_remote.id_queue' => $this->id_queue,
            'upload_presensi_remote.status' => $this->status,
            'upload_presensi_remote.waktu_diupload' => $this->waktu_diupload,
        ]);

        $query->andFilterWhere(['like', 'upload_presensi_remote.SN', $this->SN])
            ->andFilterWhere(['like', 'upload_presensi_remote.file', $this->file])
            ->andFilterWhere(['like', 'upload_presensi_remote.user_pengupload', $this->user_pengupload]);

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

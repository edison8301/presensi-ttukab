<?php

namespace app\modules\tandatangan\models;

use app\components\Session;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tandatangan\models\Berkas;

/**
 * BerkasSearch represents the model behind the search form of `app\modules\tandatangan\models\Berkas`.
 */
class BerkasSearch extends Berkas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_berkas_status', 'id_aplikasi'], 'integer'],
            [['nama', 'uraian', 'berkas_mentah', 'berkas_tandatangan', 'nip_tandatangan', 'waktu_tandatangan', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['id_berkas_jenis', 'id_instansi', 'bulan', 'tahun'], 'safe'],
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
        $query = Berkas::find();

        $this->load($params);

        // add conditions that should always apply here
        if(User::isPegawai()) {
            $query->andWhere(['nip_tandatangan' => User::getNipPegawai()]);
        }

        if(User::isInstansi()) {
            $query->andWhere(['id_instansi' => User::getIdInstansi()]);
        }

        $query->andFilterWhere(['tahun' =>  Session::getTahun()]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_berkas_status' => $this->id_berkas_status,
            'bulan' => $this->bulan,
            'waktu_tandatangan' => $this->waktu_tandatangan,
            'id_aplikasi' => $this->id_aplikasi,
            'id_instansi' => $this->id_instansi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'uraian', $this->uraian])
            ->andFilterWhere(['like', 'berkas_mentah', $this->berkas_mentah])
            ->andFilterWhere(['like', 'berkas_tandatangan', $this->berkas_tandatangan])
            ->andFilterWhere(['like', 'nip_tandatangan', $this->nip_tandatangan]);

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

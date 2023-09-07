<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public $nama_pegawai;
    public $nip_pegawai;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','id_role','id_pegawai','id_instansi'], 'integer'],
            [['username', 'password'], 'safe'],
            [['nama_pegawai','nip_pegawai'], 'safe']
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

        $query = User::find();

        if($this->nama_pegawai!=null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like','pegawai.nama',$this->nama_pegawai]);
        }

        if($this->nip_pegawai!=null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like','pegawai.nip',$this->nip_pegawai]);
        }

        $query->andFilterWhere([
            'user.id' => $this->id,
            'user.id_role' => $this->id_role,
            'user.id_instansi' => $this->id_instansi
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.password', $this->password]);

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

<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\kinerja\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'jabatan_id', 'atasan_id', 'unit_kerja', 'jabatan_struktural', 'rekan_id', 'super_user'], 'integer'],
            [['password', 'nama', 'gender', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'email', 'foto', 'nip', 'grade', 'created_date'], 'safe'],
            [['no_id_absensi'],'safe']
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'jabatan_id' => $this->jabatan_id,
            'tanggal_lahir' => $this->tanggal_lahir,
            'atasan_id' => $this->atasan_id,
            'unit_kerja' => $this->unit_kerja,
            'jabatan_struktural' => $this->jabatan_struktural,
            'rekan_id' => $this->rekan_id,
            'created_date' => $this->created_date,
            'super_user' => $this->super_user,
        ]);

        $query->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'no_id_absensi', $this->no_id_absensi])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'grade', $this->grade]);

        $query->andWhere('nama IS NOT NULL AND nama != :nama',[':nama'=>'']);

        return $dataProvider;
    }
}

<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * KetidakhadiranSearch represents the model behind the search form of `app\modules\absensi\models\Ketidakhadiran`.
 */
class KetidakhadiranJamKerjaSearch extends KetidakhadiranJamKerja
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_jam_kerja', 'id_ketidakhadiran_jam_kerja_jenis',
                'id_ketidakhadiran_jam_kerja_status','bulan','id_unit_kerja'], 'integer'],
            [['tanggal', 'berkas', 'keterangan','nama_pegawai'], 'safe'],
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

        $query = KetidakhadiranJamKerja::find();
        $query->joinWith(['pegawai']);

        if(User::isPegawai()) {
            $this->id_pegawai = User::getIdPegawai();
        }

        if(User::isAdminInstansi() || User::isInstansi() || User::isOperatorAbsen()) {
            $query->andWhere(['ketidakhadiran_jam_kerja.id_instansi'=>User::getListIdInstansi()]);
        }

        if(User::isVerifikator()) {
            $query->andWhere(['ketidakhadiran_jam_kerja.id_instansi'=>User::getListIdInstansi()]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'pegawai.id_instansi' => $this->id_unit_kerja,
            'tanggal' => $this->tanggal,
            'id_jam_kerja' => $this->id_jam_kerja,
            'id_ketidakhadiran_jam_kerja_jenis' => $this->id_ketidakhadiran_jam_kerja_jenis,
            'id_ketidakhadiran_jam_kerja_status' => $this->id_ketidakhadiran_jam_kerja_status,
        ]);

        $query->andFilterWhere(['like', 'berkas', $this->berkas])
            ->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        $tanggal_awal = User::getTahun().'-01-01';
        $tanggal_akhir = User::getTahun().'-12-31';

        if($this->bulan!=null) {
            $date = date_create(User::getTahun().'-'.$this->bulan);
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
        }

        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
            ':tanggal_awal'=>$tanggal_awal,
            ':tanggal_akhir'=>$tanggal_akhir
        ]);

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

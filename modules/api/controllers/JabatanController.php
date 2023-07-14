<?php


namespace app\modules\api\controllers;

use app\models\InstansiPegawai;
use app\models\Jabatan;
use Yii;
use yii\web\Controller;

class JabatanController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionIndex($limit=300)
    {
        $query = Jabatan::find();
        $query->andFilterWhere(['id' => @$_GET['id']]);
        $query->andFilterWhere(['id_jabatan_evjab' => @$_GET['id_jabatan_evjab']]);
        $query->andFilterWhere(['id_jenis_jabatan' => @$_GET['id_jenis_jabatan']]);
        $query->andFilterWhere(['id_instansi' => @$_GET['id_instansi']]);
        $query->andFilterWhere(['id_instansi_bidang' => @$_GET['id_instansi_bidang']]);
        $query->andFilterWhere(['id_jabatan_eselon' => @$_GET['id_jabatan_eselon']]);
        $query->andFilterWhere(['id_induk' => @$_GET['id_induk']]);
        if($limit !== null)
        {
            $query->limit = $limit;
        }

        return $query->all();
    }

    public function actionView($id)
    {
        $model = Jabatan::findOne($id);

        if($model === null) {
            Yii::$app->response->statusCode = 403;
            return [
                'message' => 'Jabatan tidak ditemukan',
                'status' => 403
            ];
        }
        
        return [
            'status' => 200,
            'data' => $model
        ];
    }
}


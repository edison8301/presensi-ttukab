<?php


namespace app\commands;


use app\modules\remote\models\UploadPresensiRemote;
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UploadPresensiRemoteController extends Controller
{
    public function actionSend($id)
    {
        $model = $this->findModel($id);
        $client = new Client();
        /** @var Request $request */
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['targetRest'] . '?r=api/upload-presensi-rest/create')
            ->setData([
                'id_upload_presensi_remote' => $model->id,
                'SN' => $model->SN,
                'token' => Yii::$app->params['token'],
            ])
            ->addFile('fileInstance', Yii::getAlias('@app/web/uploads/' . $model->file));
        $response = $request->send();
        /** @var Response $response */
        if ($response->isOk) {
            echo json_encode($response->getData());
        } else {
            echo $response->getStatusCode();
        }
    }

    /**
     * Finds the UploadPresensiRemote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UploadPresensiRemote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UploadPresensiRemote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

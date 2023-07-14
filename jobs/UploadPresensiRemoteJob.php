<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 3/6/2018
 * Time: 9:07 AM
 */

namespace app\jobs;

use app\modules\remote\models\UploadPresensiRemote;
use Yii;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\queue\JobInterface;
use yii\web\Response;

class UploadPresensiRemoteJob extends BaseJob implements JobInterface
{
    /**
     * @var int
     */
    public $id;

    public function execute($queue)
    {
        $model = UploadPresensiRemote::findOne($this->id);
        $client = new Client();
        /** @var Request $request */
        $request = $client->createRequest()
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['targetRest'] . '?r=api/upload-presensi-rest/create')
            ->setData([
                'id_upload_presensi_remote' => $model->id,
                'SN' => $model->SN,
                'token' => Yii::$app->params['token']
            ])
            ->addFile('fileInstance', Yii::getAlias('@app/web/uploads/' . $model->file));
        $response = $request->send();
        /** @var Response $response */
        if ($response->isOk) {
            echo json_encode($response->getData());
        } else {
            echo $response->getStatusCode();
            var_dump($response->getData());
        }
    }
}

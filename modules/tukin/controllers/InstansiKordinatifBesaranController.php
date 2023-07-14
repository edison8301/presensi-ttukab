<?php

namespace app\modules\tukin\controllers;

use app\components\Helper;
use app\models\User;
use app\modules\tukin\models\InstansiKordinatifBesaran;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class InstansiKordinatifBesaranController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['editable-update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $posted = Yii::$app->request->post();
            $post = ['InstansiKordinatifBesaran' => $posted];

            if ($model->load($post)) {
                if ($model->save())
                    $out = Json::encode(['output'=> Helper::rp($model->besaran), 'message'=>'']);
                else
                    $out = Json::encode(['output'=>Helper::rp($model->besaran), 'message'=>'Internal Server Error']);
            } else {
                $out = Json::encode(['output'=>Helper::rp($model->besaran), 'message'=>'']);
            }

            return $out;
        }
    }

    /**
     * Finds the Instansi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiKordinatifBesaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiKordinatifBesaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException("asdasd $id");
        }
    }

}

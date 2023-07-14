<?php

namespace app\modules\tukin\controllers;

use app\models\User;
use app\modules\tukin\models\Instansi;
use Yii;
use app\modules\tukin\models\InstansiSerapanAnggaran;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InstansiSerapanAnggaranController implements the CRUD actions for InstansiSerapanAnggaran model.
 */
class InstansiSerapanAnggaranController extends Controller
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
                        'actions' => ['index', 'view', 'update', 'anggaran'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin() || User::isMapping();
                        }
                    ],
                    [
                        'actions' => ['profil'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isInstansi();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InstansiSerapanAnggaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Instansi::find()
            ->with('manyInstansiSerapanAnggaranTahun')
            ->andWhere(['id_instansi_jenis' => 1]);
        if (User::isMapping()) {
            $query->andWhere(['in', 'id', User::getListIdInstansi()]);
        }
        return $this->render('index', [
            'allInstansi' => $query->all(),
        ]);
    }

    /**
     * Displays a single InstansiSerapanAnggaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing InstansiSerapanAnggaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {
            $referrer = $_POST['referrer'];
            if ($model->save()) {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);
            }
            Yii::$app->session->setFlash('error','Data gagal disimpan. Silahkan periksa kembali isian Anda.');
        }

        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);

    }

    /**
     * Deletes an existing InstansiSerapanAnggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->delete()) {
            Yii::$app->session->setFlash('success','Data berhasil dihapus');
        } else {
            Yii::$app->session->setFlash('error','Data gagal dihapus');
        }

        return $this->redirect(Yii::$app->request->referrer);


    }

    /**
     * Finds the InstansiSerapanAnggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstansiSerapanAnggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstansiSerapanAnggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $out = Json::encode(['output'=>'', 'message'=>'']);
            $id = Yii::$app->request->post('editableKey');
            $model = InstansiSerapanAnggaran::findOne($id);
            if ($model !== null) {
                $posted = Yii::$app->request->post();
                $post = ['InstansiSerapanAnggaran' => $posted];
                if ($model->load($post)) {
                    $model->save();
                }
                $out = Json::encode(['output' => '', 'message' => '']);
            }
            return $out;
        }
    }
}

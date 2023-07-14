<?php

namespace app\modules\absensi\controllers;

use Yii;
use app\modules\absensi\models\Keterangan;
use app\modules\absensi\models\KeteranganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * KeteranganController implements the CRUD actions for Keterangan model.
 */
class KeteranganController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Keterangan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KeteranganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Keterangan model.
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
     * Creates a new Keterangan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($nip=null,$tanggal=null)
    {
        $model = new Keterangan();

        $model->nip = $nip;
        $model->tanggal = $tanggal;
        $model->status = Keterangan::TUNGGU;

        $referrer = Yii::$app->request->referrer;
        if(isset($_POST['referrer']))
            $referrer = $_POST['referrer'];        

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $lampiran = UploadedFile::getInstance($model, 'lampiran');

            if($lampiran !== null) {
                $model->lampiran = $lampiran->baseName . Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')) . '.' . $lampiran->extension;
            }

            if($model->save()) {

                if($lampiran !== null) {
                    $path = Yii::getAlias('@app').'/web/uploads/';
                    $lampiran->saveAs($path.$model->lampiran, false);
                }
                return $this->redirect($referrer);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'referrer'=>$referrer
        ]);
    }
 

    /**
     * Updates an existing Keterangan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $referrer = Yii::$app->request->referrer;

        if ($model->load(Yii::$app->request->post())) {

            if($model->save())
            {
                Yii::$app->session->setFlash('success','Data berhasil disimpan.');
                return $this->redirect($referrer);   
            }
            
        }
        
        return $this->render('update', [
            'model' => $model,
            'referrer'=>$referrer
        ]);
    }

    /**
     * Deletes an existing Keterangan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Keterangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Keterangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Keterangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

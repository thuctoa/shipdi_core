<?php

namespace app\controllers;

use Yii;
use app\models\Location;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\controllers\SiteController;
/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                   // 'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Location::find(),
        ]);
       
        if(isset($_GET['xoahet'])&&isset($_GET['date'])&&isset($_GET['session'])){
            $date=$_GET['date'];
            $session=$_GET['session'];
            $location= Location::find()->where(['date' => $date,'session'=>$session])->all();
            foreach ($location as $val){
                $this->findModel($val['id'])->delete();
            }
            
        }
        if(isset($_GET['xoamavandon'])){
            $xoamavandon=$_GET['xoamavandon'];
            $location= Location::find()->where(['idorder' => $xoamavandon])->all();
            foreach ($location as $val){
                $this->findModel($val['id'])->delete();
            }
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
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
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();
        if(isset($_GET['x'])){
            echo $_GET['x'].','.$_GET['y'];
            $model->x=$_GET['x'];
            $model->y=$_GET['y'];
            
            $model->address= $this->getaddress($model->x, $model->y);
            
            $model->time=$_GET['time'];
            $model->date=Yii::$app->formatter->asDatetime($_GET['date'], "php:Y-m-d");
            $model->session=$_GET['session'];
            $model->idorder=$_GET['idorder'];
            $model->save();
            
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    protected function getaddress($lat,$lng)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        if($status=="OK")
        return $data->results[0]->formatted_address;
        else
        return false;
    }
    
    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Location model.
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
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\Location;
use  yii\helpers\VarDumper;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'language' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $location = Location::find()->all();
        $l=count($location);//so phan tu cua khu vuc
        $array=array();
        $tam=array();
        $sokhuvuc=8;
        //khoi tao mang cac khu vuc
        for($i=0;$i<$sokhuvuc;$i++){
            $array[$i]=array();
            //coppy sau (coppy ro rang)
            $tam[$i]['x']=$location[$i]['x'];
            $tam[$i]['y']=$location[$i]['y'];
            //them tam cua cum ban dau
            array_push($array[$i],$tam[$i]);
        }
        
        for($j=$sokhuvuc;$j<$l;$j++){//quet cac diem tren ban do
            $thuockhuvuc=0;//mac dinh la gan cum 0 nhat
            $khoangcachbenhat=99999999;//khoang cach be nhat ban dau
            for($i=0;$i<$sokhuvuc;$i++){//quet cac khu vuc
                //tinh khoang cach cua diem ay voi cac tam
                $khoangcach=$this->khoangcach($location[$j], $tam[$i]);
                if($khoangcach< $khoangcachbenhat ){
                    $khoangcachbenhat=$khoangcach;
                    $thuockhuvuc=$i;//chon khu vuc thu i la gan diem do nhat
                }
            }
            //bo diem nay vao khu vuc do
            array_push($array[$thuockhuvuc], $location[$j]);
        }
        //phan cum ro rang
        for($j=0;$j<10;$j++){
            //cap nhat lai tam cac khu vuc
            for($i=0;$i<$sokhuvuc;$i++){
                $sophantu=  count($array[$i]);
                $tam[$i]['x']=0;
                $tam[$i]['y']=0;
                for($k=0;$k<$sophantu;$k++){
                    $tam[$i]['x']+=$array[$i][$k]['x']/$sophantu;
                    $tam[$i]['y']+=$array[$i][$k]['y']/$sophantu;
                }
                //huy bo mang da cat nhap lai tam
                unset($array[$i]);
                $array[$i]=array();
            }
            
            //phan cum lai
            for($t=0;$t<$l;$t++){//quet cac diem tren ban do
                for($i=0;$i<$sokhuvuc;$i++){
                    $thuockhuvuc=0;//mac dinh la gan cum 0 nhat
                    $khoangcachbenhat=99999999;//khoang cach be nhat ban dau
                    for($i=0;$i<$sokhuvuc;$i++){//quet cac khu vuc
                        //tinh khoang cach cua diem ay voi cac tam
                        $khoangcach=$this->khoangcach($location[$t], $tam[$i]);
                        if($khoangcach< $khoangcachbenhat ){
                            $khoangcachbenhat=$khoangcach;
                            $thuockhuvuc=$i;//chon khu vuc thu i la gan diem do nhat

                        }
                    }
                    //bo diem nay vao khu vuc do
                    array_push($array[$thuockhuvuc], $location[$t]);
                }
            }
        }
        //tim bao loi cua moi cum
//        for($i=0;$i<$sokhuvuc;$i++){
//            $this->baoloi($array[$i]);
//        }
        
        return $this->render('index',[
            'tam'=>$tam,
            'arraylocation'=>$location,
            'array'=>$array,
            'sokhuvuc'=>$sokhuvuc,
        ]);
    }
    
    public function actionRoutes()
    {
        return $this->render('routes');
    }
    
    /**
     * Ajax handler for language change dropdown list. Sets cookie ready for next request
     */
    public function actionLanguage()
    {
        if ( Yii::$app->request->post('_lang') !== NULL && array_key_exists(Yii::$app->request->post('_lang'), Yii::$app->params['languages']))
        {
            Yii::$app->language = Yii::$app->request->post('_lang');
            $cookie = new yii\web\Cookie([
            'name' => '_lang',
            'value' => Yii::$app->request->post('_lang'),
            ]);
            Yii::$app->getResponse()->getCookies()->add($cookie);
        }
        Yii::$app->end();
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    
     public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    protected function khoangcach( $a, $b){
        $x=abs($a['x']-$b['x']);
        $y=abs($a['y']-$b['y']);
        if($x>$y)
            return $x;
        else
            return $y;
    }
    protected function baoloi($a){
        $array=$a;
        $l=  count($a);
        $b=[];
        $vitrdiem=0;
        $diem['x']=0;$diem['y']=0;
        for($i=0;$i<$l;$i++){
            //tim diem co tung do lon nhat trong mang
            if($a[$i]['y']>$diem['y']){
                $vitrdiem=$i;
                $diem['x']=$a[$i]['x'];$diem['y']=$a[$i]['y'];
            }
        }
        array_push($b, $diem);
        //loai bo di mot phan tu diem trong mang ban dau
        unset($array[$vitrdiem]);

        //tim diem bien thu hai
        $diemdau;
        foreach ($array as $key=>$val){
            $diemdau=$val;
            break;
        }
        
        $vectora['x']=$diemdau['x']-$diem['x'];
        $vectora['y']=$diemdau['y']-$diem['y'];
        
        //tinh goc lon nhat
        $cos=1;
        foreach ($array as $key=>$val){
            $diemdau=$val;
            $vectorb['x']=$diemdau['x']-$diem['x'];
            $vectorb['y']=$diemdau['y']-$diem['y'];
            $gtcos=$this->cos($vectora, $vectorb);
            //tim diem thu ba tao goc lon nhat tuc la cosin nho nhat
            if($gtcos<$cos){
                $cos=$gtcos;
                $vitrdiem=$key;
            }
            
        }
        array_push($b, $array[$vitrdiem]);
        //loai bo tiep diem nay
        unset($array[$vitrdiem]);
        //print_r($b);
        $this->xem($b);
        die;
    }
    protected function cos($vectora,$vectorb){
        $a=  sqrt($vectora['x']*$vectora['x']+$vectora['y']*$vectora['y']);
        $b=  sqrt($vectorb['x']*$vectorb['x']+$vectorb['y']*$vectorb['y']);
        $c=$vectora['x']*$vectorb['x']+$vectora['y']*$vectorb['y'];
        return $c/($a*$b);
    }
     protected function xem($log){
        echo '<pre>';
            print_r($log);
        echo  '</pre>';
     }
}

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
    public function actionCreatelocation(){
        
        $xem=  Location::find()->where(['date' => date("Y-m-d")])->all();
        //$this->xem($xem);
        //lay thoi gian hien tai lam mac dinh cua ngay hom do
        return $this->render('createlocation',[
            'xem'=>$xem,
        ]);
    }
    
    public function actionLocation() {
        $date=date("Y-m-d");
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $ca=1;
        if(date("a")=="pm"){
            if(date("g")>6){
                $ca=3;
            }else{
                $ca=2;
            }

        }
        $thoigiantheot1=2;
        $thoigiantheot2=3;
        $thoigiantheot3=4;

        if(isset($_GET['t'])){
            $t=$_GET['t'];
            $thoigiantheot1=$_GET['thoigianhoatdongt1'];
            $thoigiantheot2=$_GET['thoigianhoatdongt2'];
            $thoigiantheot3=$_GET['thoigianhoatdongt3'];
            if($t==3){
                if($thoigiantheot3<=0){
                    $thoigiantheot3=4;
                }
                if($thoigiantheot3<=$thoigiantheot2){
                    $thoigiantheot2=$thoigiantheot3;
                }
                if($thoigiantheot3<=$thoigiantheot1){
                    $thoigiantheot1=$thoigiantheot3;
                }
            }
            if($t==2){
                if($thoigiantheot2<=0){
                    $thoigiantheot2=3;
                }
                if($thoigiantheot3<=$thoigiantheot2){
                    $thoigiantheot3=$thoigiantheot2;
                }
                if($thoigiantheot2<=$thoigiantheot1){
                    $thoigiantheot1=$thoigiantheot2;
                }
            }
            if($t==1){
                if($thoigiantheot1<=0){
                    $thoigiantheot1=2;
                }
                if($thoigiantheot3<=$thoigiantheot1){
                    $thoigiantheot3=$thoigiantheot1;
                }
                if($thoigiantheot2<=$thoigiantheot1){
                    $thoigiantheot2=$thoigiantheot1;
                }
            }
        }
        if(isset($_GET['ngay'])){
           $date= date("Y-m-d", strtotime($_GET['ngay']));
           $ca=$_GET['ca'];
        }
        $location = Location::find()->where(['date' => $date,'session'=>$ca])->all();
        $vitricongty=['x'=> 20.9930851, 'y'=> 105.8259845];
       // $vitricongty = $this->tamarray($location);
        
        $khoangcacht1=$this->tongkhoangcachtheot($location, $vitricongty, 1);
        $khoangcacht2=$this->tongkhoangcachtheot($location, $vitricongty, 2);
        $khoangcacht3=$this->tongkhoangcachtheot($location, $vitricongty, 3);
        $vantoctrungbinh=30;
        $socum=round($khoangcacht1/($thoigiantheot1*$vantoctrungbinh/1000));
        if($socum<round($khoangcacht2/($thoigiantheot2*$vantoctrungbinh/1000))){
            $socum=round($khoangcacht2/($thoigiantheot2*$vantoctrungbinh/1000));
        }else if($socum<round($khoangcacht3/($thoigiantheot3*$vantoctrungbinh/1000))){
            $socum=round($khoangcacht3/($thoigiantheot3*$vantoctrungbinh/1000));
        }
        if(isset($_GET['socum'])){
            $socum=$_GET['socum'];
            if($socum<1){
                $socum=1;
            }
        }
        
        if($socum>count($location)-1){
            $socum=count($location)-1;
        }
        if($socum<1){
            $socum=1;
        }
        //tinh trung binh khoang cach
        $trungbinhkhoangcacht1=$khoangcacht1/$socum;
        $trungbinhkhoangcacht2=$khoangcacht2/$socum;
        $trungbinhkhoangcacht3=$khoangcacht3/$socum;
        
        $diembien=  $this->diembien($location);
        $tamcum=  $this->tamcum($diembien, $socum);
        //tu so tam cum co duoc ta se ra duoc so cum
        $phancum =  $this->phamcum($tamcum, $location);
        //$tamcum=  $this->phantamlai($phancum, $tamcum);
        
        //phan chia du so khu vuc can thiet
        if(count($phancum)<$socum){
            $themcuvao=$socum-count($phancum);
            for($i=0;$i<$themcuvao;$i++){
                $kvm=  $this->khuvuckhoangcachmax($phancum, $vitricongty);
                $tamcum = $this->themtam($tamcum, $kvm, $phancum, $vitricongty);
                //phan cum lai
                $phancum =  $this->phamcum($tamcum, $location);
               // $tamcum=  $this->phantamlai($phancum, $tamcum);
            }
            
        }
       
        for($j=0; $j<10;$j++){
            //$vitricongty=  $this->tamarray($tamcum);
            if(count($tamcum)>1){
                $lenlocation= 2* count($location);
                for($i=0;$i<$lenlocation;$i++){
                    //Can bang thoi gian di chuyen tren moi cum
                    $kvm=  $this->khuvuckhoangcachmax($phancum, $vitricongty);
                   // //echo 'Khu vuc ma la: '.$kvm.'<br>';
                    $kvbbc=$this->khuvucbebencach($kvm, $tamcum, $phancum, $vitricongty);
                    ////echo 'Khu vuc be ben cach la: '. $kvbbc.'<br>' ;
                    //cat bot diem sang cum be
                    $diemcatbot=  $this->vitrigannhat($phancum[$kvm], $tamcum[$kvbbc]);
                    array_push($phancum[$kvbbc],$phancum[$kvm][$diemcatbot] );
                    //loai bo diem cat bot ben khu vuc max
                    unset($phancum[$kvm][$diemcatbot]);
                    //phan tam lai
                    $tamcum=  $this->phantamlai($phancum, $tamcum);

                }
            }
            $phancum =  $this->phamcum($tamcum, $location);
            $tamcum=  $this->phantamlai($phancum, $tamcum);
        }
         //tinh lai thoi gian
        $thoigiantheot1=0;
        $thoigiantheot2=0;
        $thoigiantheot3=0;
        foreach ($phancum as $val){
            $khoangcacht1=$this->tongkhoangcachtheot($val, $vitricongty, 1);
            $khoangcacht2=$this->tongkhoangcachtheot($val, $vitricongty, 2);
            $khoangcacht3=$this->tongkhoangcachtheot($val, $vitricongty, 3);

            $thoigiantheot1+=$khoangcacht1*1000/$vantoctrungbinh;
            $thoigiantheot2+=$khoangcacht2*1000/$vantoctrungbinh;
            $thoigiantheot3+=$khoangcacht3*1000/$vantoctrungbinh;
        }

//        echo $trungbinhkhoangcacht1.'<br>';
//        echo $trungbinhkhoangcacht2.'<br>';
//        echo $trungbinhkhoangcacht3.'<br>';
        $thoigiantheot1=$thoigiantheot1/$socum;
        $thoigiantheot2=$thoigiantheot2/$socum;
        $thoigiantheot3=$thoigiantheot3/$socum;
//        
//        //loc cac don t1, t2 va t3 thanh 3 mang
//        $array1=  $this->locdont($location, 1);
//        $array2=  $this->locdont($location, 2);
//        $array3=  $this->locdont($location, 3);
//        
//        $khuvuc=[];
//        while($this->tongkhoangcachtheot($khuvuc, $vitricongty, 1)<$trungbinhkhoangcacht1){
//            $vtg=  $this->vitrigannhat($array1, $tamcum[0]);
//            array_push($khuvuc,$array1[$vtg] );
//            unset($array1[$vtg]);
//        }        
//        while($this->tongkhoangcachtheot($khuvuc, $vitricongty, 2)<$trungbinhkhoangcacht2){
//            $vtg=  $this->vitrigannhat($array2, $tamcum[0]);
//            array_push($khuvuc,$array2[$vtg] );
//            unset($array2[$vtg]);
//        } 
//        while($this->tongkhoangcachtheot($khuvuc, $vitricongty, 3)<$trungbinhkhoangcacht3){
//            $vtg=  $this->vitrigannhat($array3, $tamcum[0]);
//            array_push($khuvuc,$array3[$vtg] );
//            unset($array3[$vtg]);
//        } 
//        echo $this->tongkhoangcachtheot($khuvuc, $vitricongty, 1).'<br>';
//        echo $this->tongkhoangcachtheot($khuvuc, $vitricongty, 2).'<br>';
//        echo $this->tongkhoangcachtheot($khuvuc, $vitricongty, 3).'<br>';
//        $phancum[0]=$khuvuc;
//        
//        
        
        //ve lai diem bien
        $diembientheocum =  $this->diembientheocum($phancum);
        
        if(isset($_GET['inkhuvuc'])){
            if($_GET['inkhuvuc']!=-1){
                $vitridau= $vitricongty;
                $bandokhuvuc=$this->bandokhuvuc($phancum[$_GET['inkhuvuc']],$vitridau);
                
                $sobando=[];
                if(count($bandokhuvuc)>9){
                    while(count($bandokhuvuc)>0){
                        $bandocon=[];
                        $i=0;
                        
                        if(count($sobando)>0){
                             array_push($bandocon, $vitridau);
                        }
                        while($i<9){
                            if($i==8){
                                $vitridau=  current($bandokhuvuc);
                            }
                            array_push($bandocon, array_shift($bandokhuvuc));
                            if(count($bandokhuvuc)==0){
                                break;
                            }
                            $i++;
                        }
                        array_push($sobando, $bandocon);
                    }
                    
                }else{
                    array_push($sobando, $bandokhuvuc);
                }
                return $this->render('inkhuvuc', [
                    'sobando'=> $sobando,
                    ]
                );
            }
        }
        
        if(isset($_GET['laydiachicum'])){
            $ldcc=$_GET['laydiachicum'];
            if($ldcc!=-1){
                if($ldcc==-10){
                    $laydiachitoanbo=Location::find()->where(['date' => $date,'session'=>$ca])
                            ->orderBy('idorder')
                            ->all();
                    $toanbokhuvuc=[];
                    foreach ($laydiachitoanbo as $val){
                        $phantugop=[];
                        $phantugop['idorder']=$val['idorder'];
                        $phantugop['address']=$val['address'];
                        $phantugop['khuvuc']= $this->thuockhuvuc($val['idorder'], $phancum);
                        array_push($toanbokhuvuc, $phantugop);
                        
                    }
                    return $this->render('laydiachicum', [
                        'bandokhuvuc'=> $toanbokhuvuc,
                        'laytoanbo'=>1,
                        ]
                    );
                }else{
                    $vitridau= $vitricongty;
                    $bandokhuvuc=$this->bandokhuvuc($phancum[$ldcc],$vitridau);
                    return $this->render('laydiachicum', [
                        'bandokhuvuc'=> $bandokhuvuc,
                        'diachicum'=>$ldcc,
                        ]
                    );
                }
            }
        }
       
        
        //lay thoi gian hien tai lam mac dinh cua ngay hom do
        return $this->render('location', [
            'location' => $location,
            'date'=>$date,
            'ca'=>$ca,
            'diembien'=>$diembien,
            'tamcum'=>$tamcum,
            'phancum'=>$phancum,
            'diembientheocum' =>$diembientheocum,
            'socum'=>$socum,
            'thoigiantheot1'=>$thoigiantheot1,
            'thoigiantheot2'=>$thoigiantheot2,
            'thoigiantheot3'=>$thoigiantheot3,
        ]);
    }
    //loc don t1, t2 va t3 trong location
    protected function locdont($location, $t){
        $arrayt=[];
        foreach ($location as $val){
            if($val['time']==$t){
                array_push($arrayt, $val);
            }
        }
        return $arrayt;
    }

    protected function thuockhuvuc($mavandon, $phancum){
        foreach ($phancum as $key=>$val){
            foreach ($val as $value){
                if($mavandon==$value['idorder']){
                    return $key;
                }
            }
        }
        
    }

    public function actionInkhuvuc($array){
        return $this->render('inkhuvuc', [
            'array' => $array,
            ]
        );
    }

    protected function vitriduonggannhat($tamcum, $vitrixet, $vitricongty){
        $vtn=0;
        $gtln=-1;
        foreach ($tamcum as $key=>$val){
            if($this->dauduongthang($tamcum[$vitrixet], $vitricongty, $val)>0){
                $gt= $this->cos($this->vector($vitricongty, $tamcum[$vitrixet]), $this->vector($vitricongty, $val));
                if($gtln<=$gt){
                    $gtln=$gt;
                    $vtn=$key;
                    
                }
            }
        }
        return $vtn;
    }
    
     protected function vitriamgannhat($tamcum, $vitrixet, $vitricongty){
        $vtn=0;
        $gtln=-1;
        foreach ($tamcum as $key=>$val){
            if($this->dauduongthang($tamcum[$vitrixet], $vitricongty, $val)<0){
                $gt= $this->cos($this->vector($vitricongty, $tamcum[$vitrixet]), $this->vector($vitricongty, $val));
                if($gtln<=$gt){
                    $gtln=$gt;
                    $vtn=$key;
                }
            }
        }
        return $vtn;
    }
    
    protected function khuvucbebencach($kvm, $tamcum, $phancum, $vitricongty){
        $lencum=  count($phancum);
        if($lencum>2){
            $kvd= $this->vitriduonggannhat($tamcum, $kvm, $vitricongty);
            $kva= $this->vitriamgannhat($tamcum, $kvm, $vitricongty); 
            if($this->tongkhoangcachtheot($phancum[$kva], $vitricongty,3)
                    >$this->tongkhoangcachtheot($phancum[$kvd], $vitricongty,3)){
                return $kvd;
            }else{
                return $kva;
            }
        }else if($lencum==2){
            if($kvm ==1){
                return 0;
            }else{
                return 1;
            }
            
        }
    }

    protected function khuvuckhoangcachmax($phancum, $vitricongty){
        $kvm=0;
        $kcm=0;
        foreach ($phancum as $key=>$val){
            if(count($val)>1){
                $kc=$this->tongkhoangcachtheot($val, $vitricongty,3);
                if($kcm<$kc){
                    $kcm=$kc;
                    $kvm = $key;
                }
            }
        }
        return $kvm;
    }

    protected function phantamlai($phancum, $tamcum){
        foreach ($phancum as $key=>$val){
                $tamcum[$key]=  $this->tamarray($val);
            }
        return $tamcum;
    }

    public function actionIndex()
    {
        $company=['x'=>'20.9930851','y'=>'105.8259845'];
        $location = Location::find()->all();
        $company=  $this->tamarray($location);
        $l=count($location);//so phan tu cua khu vuc
        $array=array();
        $tam=array();
        $phankhudau=$location;
        if(isset($_GET['sokhuvuc'])){
            $sokhuvuc=$_GET['sokhuvuc'];
        }else{
            $sokhuvuc=intval($this->tongkhoangcach($location,$company)/0.09);
        }
        //khoi tao mang cac khu vuc
        for($i=0;$i<$sokhuvuc;$i++){
            $array[$i]=array();
            //them tam cua cum ban dau
            //array_push($array[$i],$company);
            array_push($array[$i],$phankhudau[$i]);
            $tam[$i]=  $phankhudau[$i];
        }

        foreach ($phankhudau as $key=>$val){
            $choncum=  $this->vitrigannhat($tam, $val);
            array_push($array[$choncum],$val);
            unset($phankhudau[$key]);
            $tam[$choncum]=  $this->tamarray($array[$choncum]);
        }
        for($j=0;$j<10;$j++){
            unset($tam);
            $tam=[];
            for($i=0;$i<$sokhuvuc;$i++){
                $tam[$i]=  $this->tamarray($array[$i]);
                unset($array[$i]);
                $array[$i]=[];
            }
            unset($phankhudau);
            $phankhudau=$location;
            foreach ($phankhudau as $key=>$val){
                $choncum=  $this->vitrigannhat($tam, $val);
                array_push($array[$choncum],$val);
                $tam[$choncum]=  $this->tamarray($array[$choncum]);
            }
        }
        

        $tongkhoangcach=array();
        $gioihankhoangcach=0;
        for($i=0;$i<$sokhuvuc;$i++){
            $tongkhoangcach[$i]= $this->tongkhoangcach($array[$i],$company);
            $gioihankhoangcach+=$tongkhoangcach[$i]/$sokhuvuc;
            //cap nhat lai tam array
            $tam[$i]=  $this->tamarray($array[$i]);
        }
        //echo $gioihankhoangcach.' la khoang cach trung binh<br>';

        $tamcum = $tam;
        for($j=0;$j<$sokhuvuc+10;$j++){  
            $vitricumnhonhat= $this->minrray($tongkhoangcach);//vi tri cum nho nhat
            $tamcum = $tam;
            unset($tamcum[$vitricumnhonhat]);//loai bo cum to nhat khoi bang xet
            while ($tongkhoangcach[$vitricumnhonhat]<$gioihankhoangcach){
                $vitrimin=0;
                $vitritrongmang=0;
                $khoangcachmin=99999;
                for($i=0;$i<$sokhuvuc;$i++){

                    if(isset($tamcum[$i])){
                        $giatrimin=$this->khoangcachgannhat($array[$i], $tam[$vitricumnhonhat]);

                        if($giatrimin<$khoangcachmin){
                            $khoangcachmin=$giatrimin;
                            $vitritrongmang=$this->vitrigannhat($array[$i], $tam[$vitricumnhonhat]);
                            $vitrimin=$i;
                        }
                    }
                }

                array_push($array[$vitricumnhonhat],$array[$vitrimin][$vitritrongmang] );
                unset($array[$vitrimin][$vitritrongmang]);


                $gioihankhoangcach=0;
                for($i=0;$i<$sokhuvuc;$i++){
                    $tongkhoangcach[$i]= $this->tongkhoangcach($array[$i],$company);
                    $gioihankhoangcach+=$tongkhoangcach[$i]/$sokhuvuc;
                    //cap nhat lai tam array
                    $tam[$i]=  $this->tamarray($array[$i]);
                }
            }
        }
        for($i=0;$i<$sokhuvuc;$i++){
            $array[$i]=  $this->duongdingannhat($array[$i], $company);
        }
        return $this->render('index',[
            'tam'=>$tam,
            'arraylocation'=>$location,
            'array'=>$array,
            'sokhuvuc'=>$sokhuvuc,
            'tongkhoangcach'=>$tongkhoangcach,
        ]);
    }
    
    protected function tongkhoangcachtheot($array, $vitricongty, $t){
        $array1=[];
        if($t==1){
            foreach ($array as $val){
                if($val['time']==1){
                    array_push($array1, $val);
                }
            }
            return $this->tongkhoangcach($array1, $vitricongty);
        }else if($t==2){
            $array1=[];
            foreach ($array as $val){
                if($val['time']==1){
                    array_push($array1, $val);
                }
            }
            $array2=[];
            foreach ($array as $val){
                if($val['time']==2){
                    array_push($array2, $val);
                }
            }
            $diemdau=  $this->vitricuoicuatongkhoangcach($array1, $vitricongty);
            return $this->tongkhoangcach($array1, $vitricongty)+$this->tongkhoangcach($array2, $diemdau);
        }else{
            $array1=[];
            foreach ($array as $val){
                if($val['time']==1){
                    array_push($array1, $val);
                }
            }
            $array2=[];
            foreach ($array as $val){
                if($val['time']==2){
                    array_push($array2, $val);
                }
            }
            $array3=[];
            foreach ($array as $val){
                if($val['time']==3){
                    array_push($array3, $val);
                }
            }
            $diemdau1=  $this->vitricuoicuatongkhoangcach($array1, $vitricongty);
            $diemdau2=  $this->vitricuoicuatongkhoangcach($array2, $diemdau1);
            return  $this->tongkhoangcach($array1, $vitricongty)
                    +$this->tongkhoangcach($array2, $diemdau1)
                    +$this->tongkhoangcach($array3, $diemdau2);
        }
        return $this->tongkhoangcach($arrayluu, $vitricongty);
    }

    protected function bandokhuvuc($array, $vitrixuatphat){
        $bando=[];
        array_push($bando, $vitrixuatphat);
        $tam=$vitrixuatphat;
        
        $array1=[];
        foreach ($array as $val){
            if($val['time']==1){
                array_push($array1, $val);
            }
        }
        $array2=[];
        foreach ($array as $val){
            if($val['time']==2){
                array_push($array2, $val);
            }
        }
        $array3=[];
        foreach ($array as $val){
            if($val['time']==3){
                array_push($array3, $val);
            }
        }
        $diemdau1=  $this->vitricuoicuatongkhoangcach($array1, $vitrixuatphat);
        $diemdau2=  $this->vitricuoicuatongkhoangcach($array2, $diemdau1);
        
        
        while (count($array1)>0){
            $vt=  $this->vitrigannhat($array1, $tam);
            $tam= $array1[$vt];
            array_push($bando, $tam);
            unset($array1[$vt]);
        }
        while (count($array2)>0){
            $vt=  $this->vitrigannhat($array2, $tam);
            $tam= $array2[$vt];
            array_push($bando, $tam);
            unset($array2[$vt]);
        }
        while (count($array3)>0){
            $vt=  $this->vitrigannhat($array3, $tam);
            $tam= $array3[$vt];
            array_push($bando, $tam);
            unset($array3[$vt]);
        }
        
        return $bando;
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
        $x=$a['x']-$b['x'];
        $y=$a['y']-$b['y'];
        return sqrt($x*$x+$y*$y);
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
    protected function goclechlonnhat($tam,$company,$array){
        $cosbenhat=1;
        $toado=0;
        $vectora['x']=$tam['x']-$company['x'];
        $vectora['y']=$tam['y']-$company['y'];
        
        foreach ($array as $key=>$val){
            $vectorb['x']=$val['x']-$company['x'];
            $vectorb['y']=$val['y']-$company['y'];
            $cos=  $this->cos($vectora, $vectorb);
            if($cos<$cosbenhat){
                $cosbenhat=$cos;
                $toado=$key;
            }
        }
        return $toado;
    }

    protected function cos($vectora,$vectorb){
        $a=  sqrt($vectora['x']*$vectora['x']+$vectora['y']*$vectora['y']);
        if($a==0)             return 1;
        $b=  sqrt($vectorb['x']*$vectorb['x']+$vectorb['y']*$vectorb['y']);
        if($b==0)            return 1;
        $c=$vectora['x']*$vectorb['x']+$vectora['y']*$vectorb['y'];
        return $c/($a*$b);
    }
    public function xem($log){
       //echo '<pre>';
           print_r($log);
       //echo  '</pre>';
    }
    protected function vitrigannhat($array,$tam){
        $minkc=99999;
        $vitri=0;
        foreach ($array as $key=>$val){
            $kc=  $this->khoangcach($val, $tam);
            if($kc<$minkc){
                $minkc=$kc;
                $vitri=$key;
            }
        }
        return $vitri;
    }
     protected function khoangcachgannhat($array,$tam){
        $minkc=99999;
        foreach ($array as $val){
            $kc=  $this->khoangcach($val, $tam);
            if($kc<$minkc){
                $minkc=$kc;
            }
        }
        return $minkc;
    }
    public function tamarray($array){
        $l=  count($array);
        $tam['x']=0;
        $tam['y']=0;
        foreach ($array as $val){
            $tam['x']+=$val['x']/$l;
            $tam['y']+=$val['y']/$l;
        }
        return $tam;
    }
    protected function tongkhoangcach($array, $vitricongty){
        $arrayluu=$array;
        $tongkhoangcach=0;
        $diemdau=$vitricongty;
        while (count($arrayluu)>0){
            $vitrigannhat=  $this->vitrigannhat($arrayluu, $diemdau);
            $tongkhoangcach+=$this->khoangcach($diemdau, $arrayluu[$vitrigannhat]);
            $diemdau=$arrayluu[$vitrigannhat];
            unset($arrayluu[$vitrigannhat]);
        }
        return $tongkhoangcach;      
        
    }
    protected function vitricuoicuatongkhoangcach($array, $vitricongty){
        $arrayluu=$array;
        $diemdau=$vitricongty;
        while (count($arrayluu)>0){
            $vitrigannhat=  $this->vitrigannhat($arrayluu, $diemdau);
            $diemdau=$arrayluu[$vitrigannhat];
            unset($arrayluu[$vitrigannhat]);
        }
        return $diemdau;      
        
    }
    protected function duongdingannhat($array,$company){
        $arrayluu=$array;
        $arrayduong=[];
        array_push($arrayduong, current($arrayluu));
        
        array_shift($arrayluu);//remove vi tri company
        $diemdau=$company;
        while (count($arrayluu)>0){
            $vitrigannhat=  $this->vitrigannhat($arrayluu, $diemdau);
            $diemdau=$arrayluu[$vitrigannhat];
            array_push($arrayduong, $arrayluu[$vitrigannhat]);
            unset($arrayluu[$vitrigannhat]);
        }
        return $arrayduong;      
    }
    //tra ve vi tri co gia tri lon nhat trong mang
    protected function maxarray($array){
        $max=0;
        $vitrimax=0;
        foreach ($array as $key=>$val){
            if($max<$val){
                $max=$val;
                $vitrimax=$key;
            }
        }
        return $vitrimax;
    }
    //tra ve vi tri co gia tri nho nhat trong mang
    protected function minrray($array){
        $min=9999999;
        $vitrimin=0;
        foreach ($array as $key=>$val){
            if($min>$val){
                $min=$val;
                $vitrimin=$key;
            }
        }
        return $vitrimin;
    }
    protected function dichchuyentrungbinh($dau,$cuoi){
        $giua['x']=($dau['x']+$cuoi['x'])/2;
        $giua['y']=($dau['y']+$cuoi['y'])/2;
        return $giua;
    }
    
    protected function dichchuyengandau($dau,$cuoi){
        $giua['x']=(5*$dau['x']+$cuoi['x'])/6;
        $giua['y']=(5*$dau['y']+$cuoi['y'])/6;
        return $giua;
    } 
    
    protected function diembien($array){
        $len=count($array);
        if($len>3){
            $diembien=[];
            $db=current($array);
            foreach ($array as $key=>$val){
                if($db['y']<$val['y']){
                    $db=$val;
                }
            }
            array_push($diembien, $db);
            
            $diema= $this->tamarray($array);
            $vectora=  $this->vector($db, $diema);
            $cosmin=1;
            $diemb=$diema;
            foreach ($array as $key=> $val){
                if($val!=$diema){
                    $vectorb=  $this->vector($db, $val);
                    $cos=  $this->cos($vectora, $vectorb);
                    if($cosmin>$cos){
                        $cosmin=$cos;
                        $diemb=$val;
                    }
                }
                
            }
            array_push($diembien, $diemb);
            
            for($i=0;$i<$len;$i++){
                $diema=$db;
                $db=$diemb;
                $vectora=  $this->vector($db, $diema);
                $cosmin=1;
                foreach ($array as $key=> $val){
                    if($val!=$diema){
                        $vectorb=  $this->vector($db, $val);
                        $cos=  $this->cos($vectora, $vectorb);
                        if($cosmin>$cos){
                            $cosmin=$cos;
                            $diemb=$val;
                            
                        }
                    }

                }
                if(in_array($diemb, $diembien)){
                    return $diembien;
                }
                array_push($diembien, $diemb);
                
            }
            
            return $diembien;
        }else{
            return $array;
        }
    }
    protected function vector($diema, $diemb){
        $vt['x']=$diemb['x']-$diema['x'];
        $vt['y']=$diemb['y']-$diema['y'];
        return $vt;
    }
    protected function khoangcachdiemaray($diem, $array){
        $tongkc=0;
        foreach ($array as $val){
            $tongkc+=$this->khoangcach($diem, $val);
        }
        return $tongkc;
    }
    
    //tinh duong cao tu dinh a cua tam giac ABC
    protected function duongcaotua($diema, $diemb, $diemc){
        $a= $this->khoangcach($diemb, $diemc);
        $b= $this->khoangcach($diema, $diemc);
        $c= $this->khoangcach($diema, $diemb);
        //tinh nua chu vi
        $p=($a + $b + $c )/2;
        return 2*(sqrt($p*($p-$a)*($p-$b)*($p-$c)))/$a;
    }

    //tinh do lech tuong doi toi hai diem
    protected function diemogiua($diem, $diem1, $diem2){
        $dolech=$this->khoangcach($diem, $diem1)- $this->khoangcach($diem, $diem2);
        if($dolech<0){
            $dolech=$dolech*-1;
        }
        return $dolech;
    }
    
    protected function tamcum($diembien, $socum){
        $tamcum=[];
        $sodiembien= count($diembien);
        if($socum<=  $sodiembien && $sodiembien>2){
            $buocnhay=  round($sodiembien/$socum);
             
            if($buocnhay<1){
                $buocnhay=1;
            }
            for($i=0; $i<$sodiembien ;$i+=$buocnhay){
                array_push($tamcum,$diembien[$i] );
                unset($diembien[$i]);
                if(count($tamcum)>=$socum){
                    break;
                }
            }
            if(count($tamcum)<$socum){
                foreach ($diembien as $val){
                    array_push($tamcum,$val );
                    if(count($tamcum)>=$socum){
                        break;
                    }
                }
            }
            return $tamcum;
        }else {
            return $diembien;
        }
    }
    protected function phamcum($tamcum, $location){
        $len= count($tamcum);
        if($len<2){
            $phancum=[];
            $phancum[0]=$location;
            return $phancum;
        }else{
            $phancum = [];
            for($i=0; $i<$len ;$i ++){
                $phancum[$i]= [];//moi mot phan tu la mot mang
            }
            
            foreach ($location as $val){
                //tim ra duoc cum gan nhat
                $vt=  $this->vitrigannhat($tamcum, $val);
                array_push($phancum[$vt], $val);
            }
            return $phancum;
        }
    }
    protected function diembientheocum($phancum ){
        $diembientheocum=[];
        foreach ($phancum as $key =>$val){
            $diembientheocum[$key] = $this->diembien($val);
        }
        return $diembientheocum;
    }
    protected function themtam($tamcum, $vitrithem, $phancum, $vitritrungtam){
        
        $lentamcum=  count($tamcum); 
        if($vitrithem >0 &&$vitrithem<$lentamcum-1){
            for ($i=$lentamcum ;$i>$vitrithem+1;$i--){
                $tamcum[$i]=$tamcum[$i-1];
            }
            
            $tamcum[$vitrithem+1] = $this->dichchuyengandau(
                                        $tamcum[$vitrithem], 
                                        $tamcum[$vitrithem+2]
                                    );
            $tamcum[$vitrithem] = $this->dichchuyengandau(
                                        $tamcum[$vitrithem], 
                                        $tamcum[$vitrithem-1]
                                    );
        }else if($vitrithem==$lentamcum-1){
            $tamcum[$vitrithem+1] = $this->dichchuyengandau(
                                        $tamcum[$vitrithem], 
                                        $tamcum[0]
                                    );
            $tamcum[$vitrithem] = $this->dichchuyengandau(
                                        $tamcum[$vitrithem], 
                                        $tamcum[$vitrithem-1]
                                    );
        }else if($vitrithem==0){
            for ($i=$lentamcum ;$i>$vitrithem+1;$i--){
                $tamcum[$i]=$tamcum[$i-1];
            }
            $tamcum[1] = $this->dichchuyengandau(
                                        $tamcum[0], 
                                        $tamcum[2]
                                    );
            $tamcum[0] = $this->dichchuyengandau(
                                        $tamcum[0], 
                                        $tamcum[$lentamcum]
                                    );
        }
        return $tamcum;
    }
    protected function dauduongthang($diem1, $diem2, $diem){
        return ($diem2['y']-$diem1['y'])*($diem['x']-$diem1['x'])
                - ($diem2['x']-$diem1['x'])*($diem['y']-$diem1['y']);
    
    }
    
}

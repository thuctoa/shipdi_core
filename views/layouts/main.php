<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            $items = [
                    ['label' => Yii::t('app','Home'), 'url' => ['/site/index']],
                    ['label' => Yii::t('app','About'), 'url' => ['/site/about']],
                    ['label' => Yii::t('app','Contact'), 'url' => ['/site/contact']],
                    
                
                ];
            if(Yii::$app->user->isGuest){
                $items[] = ['label' => Yii::t('app','Signup'), 'url' => ['/site/signup']];
                $items[] = ['label' => Yii::t('app','Login'), 'url' => ['/site/login']];
            }else{
                 if(Yii::$app->user->can('1. Director')
                         ||Yii::$app->user->can('2. Branch Director') 
                         ||Yii::$app->user->can('3. Human Resources Management') 
                    ){
                        $items[] = ['label' => 'Quản lý tài khoản', 'url' => ['/user']];
                 }
                 $items[] = ['label' => Yii::t('app','Logout').' (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']];
            }
           
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items,
            ]);
            NavBar::end();
        ?>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div  id="language-selector" class="pull-right" style="position: relative;">
            <?= \app\components\widgets\LanguageSelector::widget(); ?>
        </div>
        <div class="container">
            <p class="pull-left">&copy; <?=Yii::t('app','My Company')?> <?= date('Y') ?></p>
            <p class="pull-right"></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

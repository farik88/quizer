<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/fav.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    
<?php $this->beginBody() ?>
    
<div id="<?= (Yii::$app->controller->route === 'site/index' ? 'main-page' : 'other-page') ?>" class="wrap">
    <div class="container">
        <div class="row logotype">
            <div class="col-sm-12">
                <?php
                    if(Yii::$app->controller->route === 'site/index'){
                        echo Html::img($this->params['logo'], ['class' => 'image']);
                    } else {
                        echo Html::a(Html::img($this->params['logo'], ['class' => 'image']), Url::to(['/']));
                    }
                ?>
            </div>
        </div>
        <?= $content ?>
    </div>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/asku.png', ['alt'=>Yii::$app->name]),
        'brandUrl' => Yii::$app->controller->route === '/' ? NULL : ['/'],
        'options' => [
            'class' => 'main-bottom-nav navbar-inverse navbar-fixed-bottom',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'ASKU', 'url' => ['/']];
        $menuItems[] = ['label' => 'Контакты', 'url' => ['/contact']];
        $menuItems[] = ['label' => 'Вход', 'url' => ['/login']];
        $menuItems[] =
            '<li class="navbar-right"><a href="https://zapleo.com/"><span style="color: #555">made by </span><span style="color: orange">Zapleosoft</span></a></li>';
//        $menuItems[] = ['label' => 'Signup', 'url' => ['/signup']];
//        $menuItems[] = ['label' => 'About', 'url' => ['/about']];
    } else {
        $display_name = Yii::$app->user->identity->SocialAuth ? Yii::$app->user->identity->SocialAuth->username : Yii::$app->user->identity->username;
        $menuItems[] = ['label' => 'ASKU', 'url' => ['/']];
        $menuItems[] = ['label' => 'История', 'url' => ['/history']];
        $menuItems[] = ['label' => 'Контакты', 'url' => ['/contact']];
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . $display_name . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
        $menuItems[] =
            '<li class="navbar-right"><a href="https://zapleo.com/"><span style="color: #555">made by </span><span style="color: orange">Zapleosoft</span></a></li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
<!--    <div class="footer-text navbar-fixed-bottom" style=""><a href="https://zapleo.com/">made by zapleo</a></div>-->
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

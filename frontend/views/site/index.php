<?php
/**
 * @var $this \yii\web\View
 */


use yii\helpers\Url;
use kartik\helpers\Html;

$this->title = 'ASKU - Главная';

//$this->registerJsFile('/js/humanize-duration.js',['depends'=>\frontend\assets\AppAsset::className()]);
//$this->registerJsFile('/js/quizer.js',['depends'=>\frontend\assets\AppAsset::className()]);
?>
<div class="row title">
    <div class="col-sm-12">
        <h1>Выберите вопрос для ответа!</h1>
    </div>
</div>
<div class="quizes-index">
    <div class="body-content">
        <section class="question-previews-box">
            <div class="row">
                <div class="col-sm-12">
                <?php if(!empty($quizes)) { ?>
                    <?php foreach ($quizes as $quize){ ?>
                        <article class="col">
                            <a class="question-preview col" href="<?= Url::to(['quizes/view', 'id' => $quize['id']]) ?>">
                                <div class="inner row" style="color: white;">
                                    <span class="col-md-6 col-sm-6 col-lg-6 quetion-text"><?= $quize['text']; ?></span>
                                    <aside class="separator"></aside>
                                    <span class="col-md-6 col-sm-6 col-lg-6 timer" style="display: none"><?=$quize['end_at']-time()?></span>
                                </div>
                            </a>
                        </article>
                    <?php } ?>
                <?php }else{ ?>
                    <div class="alert alert-warning" role="alert">Извините, нам нечего вам показать :(</div>
                <?php } ?>
                </div>
            </div>
        </section>
        <?php if(!Yii::$app->user->isGuest && Yii::$app->user->id){ ?>
            <section class="add-custom-question">
                <div class="row">
                    <div class="col-sm-12">
                        <?php echo Html::a(
                                '<i class="glyphicon glyphicon-plus"></i>',
                                Url::toRoute('question/create'),
                                [
                                    'id' => 'add-custom-question-button',
                                    'title' => 'Добавить свой вопрос'
                                ]); ?>
                    </div>
                </div>
            </section>
        <?php } ?>
    </div>
</div>

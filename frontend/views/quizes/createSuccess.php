<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'ASKU - ' . 'Вопрос создан!';
?>

<div class="quizes-create-success">
    <div class="body-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Вы успешно создали свой вопрос.<br>Он появится на сайте, после проверки администратором!
                </div>
            </div>
        </div>
        <div class="row links">
            <div class="col-sm-6 col-xs-12 to-home-col">
                <?= Html::a(
                        '<span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;<span>Домой</span>',
                        Yii::$app->homeUrl,
                        [
                            'class' => 'btn btn-lg btn-primary',
                            'role' => 'button'
                        ]);
                ?>
            </div>
            <div class="col-sm-6 col-xs-12 to-create-col">
                <?= Html::a(
                        '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;<span>Еще вопрос</span>',
                        Url::toRoute('question/create'),
                        [
                            'class' => 'btn btn-lg btn-warning',
                            'role' => 'button'
                        ]);
                ?>
            </div>
        </div>
    </div>
</div>

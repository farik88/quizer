<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha;

$this->title = 'ASKU - Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <div class="body-content">
        
        <?= Alert::widget() ?>
        
        <p>
            Если у вас есть деловые предложения или другие вопросы, пожалуйста, заполните следующую форму, чтобы связаться с нами. Спасибо.
        </p>

        <div class="contact-form-wrap">
            <div class="contact-form-inner">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <div class="row">
                    <div class="col-lg-6">
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Имя') ?>
                    </div>
                    <div class="col-lg-6">
                    <?= $form->field($model, 'email') ?>
                    </div>
                </div>
                <?=''// $form->field($model, 'subject',['template'=>'{input}'])->hiddenInput(['value'=>'Notification'])?>

                <input type="hidden" id="contactform-subject" class="form-control" name="ContactForm[subject]" value="Notification">

                <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Текст') ?>

                <div class="row submit-block">
                    <div class="capcha-column col-sm-8 col-xs-12">
                        <?= $form->field($model, 'verifyCode')->widget(
                            ReCaptcha::className(),
                            ['siteKey' => Yii::$app->components['reCaptcha']['siteKey']]
                        )->label(false); ?>
                    </div>
                    <div class="submit-column col-sm-4 col-xs-12">
                        <div class="form-group">
                            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

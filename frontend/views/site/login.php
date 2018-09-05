<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'ASKU - Вход';
?>
<?= Alert::widget() ?>
<div class="front-site-login">
    <div class="heading">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Войдите через свой профиль в соц. сетях:</p>
    </div>
    <div class="row soc-buttons-row">
        <div class="col-sm-12">
            <?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>
        </div>
    </div>

    <!--<p>Please fill out the following fields to login:</p>-->

<!--    <div class="row">
        <div class="col-lg-5">
            
            <?php // $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?php // echo $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?php // echo $form->field($model, 'password')->passwordInput() ?>

                <?php // echo $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?php // echo Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?php // echo Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php // ActiveForm::end(); ?>
        </div>
    </div>-->
    
</div>

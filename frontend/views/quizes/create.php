<?php

use kartik\color\ColorInput;
use kartik\widgets\FileInput;
use mootensai\components\JsBlock;
use yii\web\View;
use yii\helpers\Json;
use kartik\form\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;
use himiklab\yii2\recaptcha\ReCaptcha;

$this->title = 'ASKU - ' . 'Создайте собственный вопрос';

JsBlock::widget(['viewFile' => '_createFormVariantsScript', 'pos'=> View::POS_END, 
    'viewParams' => [
        'class' => 'Variants',
        'relID' => 'variants',
        'model_id' => $model->id ? $model->id : 'new_model',
        'value' => Json::encode($model->variants),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);

$this->registerCssFile('/css/crop-image.css',
    ['position' => yii\web\View::POS_HEAD, 'depends' => 'frontend\assets\AppAsset']);
$this->registerCssFile('/plugins/rcrop/dist/rcrop.min.css',
    ['position' => yii\web\View::POS_HEAD, 'depends' => 'frontend\assets\AppAsset']);
$this->registerJsFile('/plugins/rcrop/dist/rcrop.min.js',
    ['position' => yii\web\View::POS_END, 'depends' => 'frontend\assets\AppAsset']);
$this->registerJsFile('/js/crop-image.js',
    ['position' => yii\web\View::POS_END, 'depends' => 'frontend\assets\AppAsset']);

$now = new DateTime();
?>

<div class="quizes-create">
    <div class="body-content">
        <?php $form = ActiveForm::begin([
            'id' => 'quize-user-create-form',
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <?php echo $form->errorSummary($model); ?>
                </div>
                <div class="form-group">
                    <?php
                        foreach (Yii::$app->session->getAllFlashes() as $key => $messge){
                            Alert::begin(['options' => ['class' => 'alert-danger']]);
                            echo $messge;
                            Alert::end();
                        }
                    ?>
                </div>
            </div>
            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <div class="col-sm-12">
                <?= $form->field($model, 'text')->textInput(['maxlength' => true, 'placeholder' => 'Сколько дней в году?', 'required' => 'required'])->label('Задайте Ваш вопрос') ?>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <?php
                        $date_value = $model->start_at ? $model::getDateInString($model->start_at) : '';
                        echo '<label>Начать опрос</label>';
                        echo DateTimePicker::widget([
                            'name' => 'UserQuize[start_at]',
                            'type' => DateTimePicker::TYPE_INPUT,
                            'options' => ['placeholder' => '01.01.' . $now->format('Y') . ' 00:00', 'required' => 'required'],
                            'value' => $date_value,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy hh:ii'
                            ]
                        ]);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                        echo '<label class="control-label">Цвет кнопки "Голосовать"</label>';
                        echo ColorInput::widget([
                            'model' => $model,
                            'attribute' => 'button_color',
                            'options' => [
                                'placeholder' => 'Выберите цвет...'
                            ]
                        ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <?php
                        $date_value = $model->end_at ? $model::getDateInString($model->end_at) : '';
                        echo '<label>Закончить опрос</label>';
                        echo DateTimePicker::widget([
                            'name' => 'UserQuize[end_at]',
                            'type' => DateTimePicker::TYPE_INPUT,
                            'options' => ['placeholder' => '01.01.' . ($now->format('Y')+1) . ' 00:00', 'required' => 'required'],
                            'value' => $date_value,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy hh:ii'
                            ]
                        ]);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    echo '<label class="control-label">Цвет фона опроса</label>';
                    echo ColorInput::widget([
                        'model' => $model,
                        'attribute' => 'background_color',
                        'options' => [
                            'placeholder' => 'Выберите цвет...'
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-12" id="quize-logo">
                <div class="form-group">
                    <label class="control-label" for="quizes-logo">Выберите логотип</label>
                    <?= FileInput::widget([
                        'language' => 'ru',
                        'name' => 'Quizes[logo]',
                        'model' => $model,
                        'pluginOptions' => [
                            'showPreview' => false,
                            'showCaption' => true,
                            'showRemove' => false,
                            'showUpload' => false
                        ],
                        'pluginEvents' => [
                            'fileselect' => 'function(event, numFiles, label) { readURL(this); }',
                        ],
                        'options' => [
                            'accept' => 'image/*',
                            'multiple' => false
                        ],
                    ]); ?>
                </div>
            </div>
            <?php if (!empty($model->logo)): ?>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="quizes-logo">Текущий логотип</label>
                        <br/>
                        <img class="img-thumbnail" src="/<?= $model->logo ?>">
                    </div>
                </div>
            <?php endif; ?>
            <div class="col-sm-12">
                <div class="form-group">
                    <?= $form->field($model, 'file_url')->textInput(['maxlength' => true, 'placeholder' => 'http://'])->label('Ссылка на файл(медиа-контент)')->hint('Image or YouTube/Vimeo video') ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Добавьте варианты ответов</label>
                    <?php
                    echo $this->render('_createFormVariants', [
                        'row' => ArrayHelper::toArray($model->variants),
                    ])
                    ?>
                </div>
            </div>
            <div class="col-sm-12 submit-block">
                <div class="col captcha col-sm-7 col-xs-12">
                    <div class="form-group">
                        <?= $form->field($model, 'reCaptcha')->widget(
                                ReCaptcha::className(),
                                ['siteKey' => Yii::$app->components['reCaptcha']['siteKey']]
                            )->label(false) ?>
                    </div>
                </div>
                <div class="col submit col-sm-5 col-xs-12">
                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

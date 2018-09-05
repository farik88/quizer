<?php

use kartik\color\ColorInput;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use kartik\datetime\DateTimePicker;
use kartik\tabs\TabsX;
use mootensai\components\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\Quizes */
/* @var $form yii\widgets\ActiveForm */

JsBlock::widget(['viewFile' => '_script', 'pos'=> View::POS_END, 
    'viewParams' => [
        'class' => 'Variants',
        'relID' => 'variants',
        'model_id' => $model->id ? $model->id : 'new_model',
        'value' => Json::encode($model->variants),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);

$this->registerCssFile('/css/crop-image.css',
    ['position' => yii\web\View::POS_HEAD, 'depends' => 'backend\assets\AppAsset']);
$this->registerCssFile('/plugins/rcrop/dist/rcrop.min.css',
    ['position' => yii\web\View::POS_HEAD, 'depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('/plugins/rcrop/dist/rcrop.min.js',
    ['position' => yii\web\View::POS_END, 'depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('/js/crop-image.js',
    ['position' => yii\web\View::POS_END, 'depends' => 'backend\assets\AppAsset']);

$file_url = $model->file_url;

if (\common\models\Quizes::FILE_TYPE_YOUTUBE == $model->file_type)
    $file_url = 'https://youtube.com/watch?v='.$model->file_url;
if (\common\models\Quizes::FILE_TYPE_VIMEO == $model->file_type)
    $file_url = 'https://vimeo.com/'.$model->file_url;

$now = new DateTime();
?>

<div class="quizes-form">
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="row">
        <div class="col-sm-12">
             <?= $form->errorSummary($model); ?>
        </div>
        <div class="col-sm-7">
            <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>
            <?= $form->field($model, 'text')->textInput(['maxlength' => true, 'placeholder' => 'Text']) ?>
            <?= $form->field($model, 'status')->dropDownList($model->statuses, []) ?>
        </div>
        <div class="col-sm-5">
            <div class="row">
                <div class="form-group"></div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php
                            $date_value = $model->isNewRecord ? '' : $model::getDateInString($model->start_at);
                            echo '<label>Start At</label>';
                            echo DateTimePicker::widget([
                                'name' => 'Quizes[start_at]',
                                'type' => DateTimePicker::TYPE_INPUT,
                                'options' => ['placeholder' => 'Select quize start time...', 'required' => 'required'],
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
                            $date_value = $model->isNewRecord ? $now->format('d.m.Y H:i') : $model::getDateInString($model->created_at);
                            echo '<label>Created At</label>';
                            echo DateTimePicker::widget([
                                'name' => 'Quizes[created_at]',
                                'type' => DateTimePicker::TYPE_INPUT,
                                'value' => $date_value,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy hh:ii'
                                ]
                            ]);
                        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?php
                            $date_value = $model->isNewRecord ? '' : $model::getDateInString($model->end_at);
                            echo '<label>End At</label>';
                            echo DateTimePicker::widget([
                                'name' => 'Quizes[end_at]',
                                'type' => DateTimePicker::TYPE_INPUT,
                                'options' => ['placeholder' => 'Select quize end time...', 'required' => 'required'],
                                'value' => $date_value,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy hh:ii'
                                ]
                            ]);
                        ?>
                    </div>
                    <div class="form-group">
                        <label>Create quize</label><br>
                        <?php if(Yii::$app->controller->action->id != 'save-as-new'): ?>
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'button_color')->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select button color ...'],
                ]); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'background_color')->widget(ColorInput::classname(), [
                'options' => ['placeholder' => 'Select background color ...'],
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="quize-logo">
            <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
                'language' => 'ru',
                'name' => 'logo',
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
        <div class="row">
            <div class="col-sm-6">
                <label class="control-label" for="quizes-logo">Current logo</label>
                <br/>
                <?= (!empty($model->logo) ? '<img class="img-thumbnail" src="/'.$model->logo.'">' : '') ?>
            </div>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'file_url')->textInput(['maxlength' => true, 'placeholder' => 'http://', 'value' => $file_url])->hint('Image or YouTube/Vimeo video') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
                $forms = [
                    [
                        'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode('Variants'),
                        'content' => $this->render('_formVariants', [
                            'row' => ArrayHelper::toArray($model->variants),
                        ]),
                    ],
                ];
                echo TabsX::widget([
                    'items' => $forms,
                    'position' => kartik\tabs\TabsX::POS_ABOVE,
                    'encodeLabels' => false,
                    'pluginOptions' => [
                        'bordered' => true,
                        'sideways' => true,
                        'enableCache' => false,
                    ],
                ]);
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

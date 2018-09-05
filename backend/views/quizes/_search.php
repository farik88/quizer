<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\QuizesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-quizes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true, 'placeholder' => 'Text']) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true, 'placeholder' => 'Status']) ?>

    <?= $form->field($model, 'created_at')->textInput(['placeholder' => 'Created At']) ?>

    <?= $form->field($model, 'updated_at')->textInput(['placeholder' => 'Updated At']) ?>

    <?php /* echo $form->field($model, 'start_at')->textInput(['placeholder' => 'Start At']) */ ?>

    <?php /* echo $form->field($model, 'end_at')->textInput(['placeholder' => 'End At']) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

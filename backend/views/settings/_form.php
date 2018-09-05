<?php

use kartik\color\ColorInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="settings-form">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= 'Settings <span class="label label-warning">'. Html::encode($model->key).'</span>' ?></h3>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <!-- /.box-header -->
        <div class="box-body">
            <?= $form->errorSummary($model); ?>
            <div class="form-group field-settings-key">
                <label class="control-label" for="settings-key">Key</label>
                <p><?= $model->key ?></p>
                <div class="help-block"></div>
            </div>

            <?= $form->field($model, 'id', ['template' => '{input}'])->input('text', ['style' => 'display:none', 'readonly' => true]); ?>

            <?= $form->field($model, 'value')->textInput(['placeholder' => 'Value']); ?>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

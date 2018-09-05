<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */

$this->title = 'Settings: ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-view">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= 'Settings <span class="label label-warning">'. Html::encode($model->key).'</span>' ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <?php
            $gridColumn = [
                ['attribute' => 'id', 'visible' => false],
                'key',
                'value:ntext',
            ];
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $gridColumn
            ]);
            ?>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

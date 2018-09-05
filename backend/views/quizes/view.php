<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Quizes */

$this->title = Yii::t('app', 'Quize') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quizes-view">

    <div class="box box-primary">
        <div class="box-body">
            <?php
                $gridColumn = [
                    ['attribute' => 'id', 'visible' => false],
                    'text',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<span class="label label-'.$model->statuses_color[$model->status].'">'.$model->statuses[$model->status].'</span>';
                        },
                    ],
                    [
                        'attribute' => 'button_color',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (empty($model->button_color))
                                return '<span class="not-set">(не задано)</span>';

                            return '<span class="badge" style="background-color: '.$model->button_color.'">&nbsp;</span>&nbsp;&nbsp;<code>' . $model->button_color . '</code>';
                        }
                    ],
                    [
                        'attribute' => 'background_color',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (empty($model->background_color))
                                return '<span class="not-set">(не задано)</span>';

                            return '<span class="badge" style="background-color: '.$model->background_color.'">&nbsp;</span>&nbsp;&nbsp;<code>' . $model->background_color . '</code>';
                        }
                    ],
                    'logo',
                    [
                        'attribute' => 'start_at',
                        'label' => 'Start at',
                        'value' => function ($model) {
                            return $model::getDateInString($model->start_at);
                        },
                    ],
                    [
                        'attribute' => 'end_at',
                        'label' => 'End at',
                        'value' => function ($model) {
                            return $model::getDateInString($model->end_at);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            return $model::getDateInString($model->created_at);
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => function ($model) {
                            return $model::getDateInString($model->created_at);
                        },
                    ]
                ];
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $gridColumn
                ]);
            ?>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>
</div>

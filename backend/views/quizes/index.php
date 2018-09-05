<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\QuizesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Quizes');
$this->params['breadcrumbs'][] = $this->title;

$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="quizes-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Quizes'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
        $colorPluginOptions =  [
            'showPalette' => true,
            'showPaletteOnly' => true,
            'showSelectionPalette' => true,
            'showAlpha' => false,
            'allowEmpty' => false,
            'preferredFormat' => 'name',
            'palette' => [
                [
                    'white', 'black', 'grey', 'silver', 'gold', 'brown',
                ],
                [
                    'red', 'orange', 'yellow', 'indigo', 'maroon', 'pink'
                ],
                [
                    'blue', 'green', 'violet', 'cyan', 'magenta', 'purple',
                ],
            ]
        ];

        $gridColumn = [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'text',
                'label' => 'Question',
                'value' => function ($model) {
                    return Html::a($model->text, Url::to(['quizes/update', 'id' => $model->id]), ['style' => 'font-weight:bold;']);
                },
                'format' => 'raw',
                'width' => '40%',
            ],
            [
                'attribute' => 'status',
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<span class="label label-'.$model->statuses_color[$model->status].'">'.$model->statuses[$model->status].'</span>';
                },
            ],
            [
                'attribute' => 'button_color',
                'label' => 'Button color',
                'value' => function ($model) {
                    if (empty($model->button_color))
                        return '<span class="not-set">(не задано)</span>';

                    return '<span class="badge" style="background-color: '.$model->button_color.'">&nbsp;</span>&nbsp;&nbsp;<code>' . $model->button_color . '</code>';
                },
                'width' => '15%',
                'filterType' => GridView::FILTER_COLOR,
                'filterWidgetOptions' => [
                    'showDefaultPalette' => false,
                    'pluginOptions' => $colorPluginOptions,
                ],
                'vAlign' => 'middle',
                'format' => 'raw'
            ],
            [
                'attribute' => 'background_color',
                'label' => 'Background color',
                'value' => function ($model) {
                    if (empty($model->background_color))
                        return '<span class="not-set">(не задано)</span>';

                    return '<span class="badge" style="background-color: '.$model->background_color.'">&nbsp;</span>&nbsp;&nbsp;<code>' . $model->background_color . '</code>';
                },
                'width' => '15%',
                'filterType' => GridView::FILTER_COLOR,
                'filterWidgetOptions' => [
                    'showDefaultPalette' => false,
                    'pluginOptions' => $colorPluginOptions,
                ],
                'vAlign' => 'middle',
                'format' => 'raw'
            ],
            [
                'attribute' => 'logo',
                'label' => 'Logo',
            ],
            [
                'attribute' => 'file_type',
                'label' => 'Media content'
            ],
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
                'label' => 'Created at',
                'value' => function ($model) {
                    return $model::getDateInString($model->created_at);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
            ],
        ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-quizes']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-question-circle" aria-hidden="true"></i>  ' . Html::encode($this->title),
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
                'exportConfig' => [
                    ExportMenu::FORMAT_PDF => false
                ]
            ]) ,
        ],
    ]); ?>

</div>

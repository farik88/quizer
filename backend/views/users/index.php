<?php
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Users');

?>
<div class="quizes-index">
    <?php 
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
            'attribute' => 'username',
            'label' => 'Username',
            'value' => function ($model) {
                return Html::a($model->username, Url::to(['users/update', 'id' => $model->id]), ['style' => 'font-weight:bold;']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'email',
            'label' => 'Email',
        ],
        [
            'attribute' => 'status',
            'label' => 'Status',
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Created at',
            'value' => function($model){
                $created_at = (new \DateTime())->setTimestamp($model->created_at)->format('d.m.Y');
                return $created_at;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
        ],
    ]; 
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
        'pjax' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<i class="fa fa-user" aria-hidden="true"></i>  ' . Html::encode($this->title),
            'before' => false,
            'after' => false,
            'footer' => false
        ],
        'export' => false,
        // your toolbar can include the additional full export menu
        'toolbar' => [],
    ]); ?>
</div>
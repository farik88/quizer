<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Quizes */

$this->title = Yii::t('app', 'Update {modelClass}', [
    'modelClass' => 'Quizes',
]) . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="quizes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

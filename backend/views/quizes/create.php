<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Quizes */

$this->title = Yii::t('app', 'Create Quizes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quizes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quizes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

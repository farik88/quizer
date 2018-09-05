<?php

use frontend\assets\ChartJsAsset;

$this->title = 'ASKU - ' . $quize->text;

if($quize->isHasAnswerFromUser($quize->id, Yii::$app->user->id)){
    ChartJsAsset::register($this);
    echo $this->render('_results', ['quize' => $quize,]);
}else{
    echo $this->render('_form', ['quize' => $quize,]);
}

<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ChartJsAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        'chart.js/dist/Chart.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
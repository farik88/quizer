<?php
 
namespace console\controllers;
 
use yii\console\Controller;
use common\models\Quizes;
 
/**
 * Test controller
 */
class CronController extends Controller {
 
    public function actionIndex() {
        echo "cron service runnning" . PHP_EOL;
    }
 
    public function actionCheckQuizesStatuses() {
        Quizes::refreshAllStatuses();
    }
 
}
<?php

namespace common\models;

use Yii;
use common\models\Answer;
use common\models\Variants;
use \common\models\base\Quizes as BaseQuizes;

/**
 * This is the model class for table "quizes".
 */
class Quizes extends BaseQuizes
{
    public $statuses = [
        'open' => 'Open',
        'wait' => 'Wait',
        'closed' => 'Closed',
        'draft' => 'Draft',
        'deleted' => 'Deleted',
    ];

    public $statuses_color = [
        'open' => 'success',
        'wait' => 'primary',
        'closed' => 'default',
        'draft' => 'warning',
        'deleted' => 'danger',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['text', 'start_at', 'end_at'], 'required'],
            [['created_at', 'updated_at', 'start_at', 'end_at'], 'integer'],
            [['text', 'file_url'], 'string', 'max' => 255],
            [['status', 'file_type'], 'string', 'max' => 50],
            [['button_color', 'background_color'], 'string', 'max' => 50],
            [['button_color', 'background_color'], 'default', 'value' => null],
            ['status', 'in', 'range' => ['open', 'wait', 'closed','draft','deleted']],
        ]);
    }
    
    public static function getActiveQuizesForUser($user_id)
    {
        $now = new \DateTime();
        
        $sql_query = "
        SELECT quizes.* 
        FROM quizes 
        INNER JOIN variants 
        ON quizes.id = variants.quize_id 
        WHERE (quizes.status ='open') 
        AND (quizes.start_at < " . $now->getTimestamp() . ") 
        AND (quizes.end_at > " . $now->getTimestamp() ." ) 
        AND (0 = (SELECT COUNT(*) 
                    FROM answers 
                    WHERE (quizes.id = answers.quize_id) 
                    AND (answers.user_id = " . $user_id . "))) 
        GROUP BY quizes.id 
        ORDER BY quizes.end_at ASC";
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql_query);
        $quizes = $command->queryAll();
        return $quizes;
    }
    
    public static function isHasAnswerFromUser($quize_id, $user_id)
    {
        $answers = Answer::find()
                ->where(['=', 'quize_id', $quize_id])
                ->andWhere(['=', 'user_id', $user_id])
                ->all();
        return $answers ? true : false;
    }
    
    public static function getAnswersDataForChart($quize_id)
    {
        $data_pack = [];
        $variants = Variants::find()->where(['quize_id' => $quize_id])->all();
        foreach ($variants as $variant){
            $count = Answer::find()->where(['variant_id' => $variant->id])->andWhere(['quize_id' => $quize_id])->count();
            $data_pack['labels'][] = $variant->text;
            $data_pack['vaues'][] = intval($count);
        }
        return $data_pack;
    }
    
    public static function getHistoryQuizesForUser($user_id)
    {
        $quizes = Quizes::find()
                ->innerJoin('answers', 'quizes.id = answers.quize_id')
                ->where('answers.user_id = ' . $user_id)
                ->all();
        return $quizes;
    }
    
}

<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Variants;

/**
 * This is the base model class for table "quizes".
 *
 * @property integer $id
 * @property string $text
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $start_at
 * @property integer $end_at
 * @property string $button_color
 * @property string $background_color
 * @property string $logo
 * @property string $file_url
 * @property string $file_type
 * 
 * @property \common\models\Variants[] $variants
 */
class Quizes extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    const FILE_TYPE_IMAGE = 'image';
    const FILE_TYPE_YOUTUBE = 'youtube';
    const FILE_TYPE_VIMEO = 'vimeo';

    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'variants'
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'start_at', 'end_at'], 'required'],
            [['created_at', 'updated_at', 'start_at', 'end_at'], 'integer'],
            [['text', 'file_url'], 'string', 'max' => 255],
            [['status', 'file_type'], 'string', 'max' => 50],
            [['button_color', 'background_color'], 'string', 'max' => 50],
            [['button_color', 'background_color'], 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quizes';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Question'),
            'status' => Yii::t('app', 'Status'),
            'start_at' => Yii::t('app', 'Start At'),
            'end_at' => Yii::t('app', 'End At'),
            'button_color' => Yii::t('app', 'Button color'),
            'background_color' => Yii::t('app', 'Background color'),
            'logo' => Yii::t('app', 'Logo'),
            'file_url' => 'File url',
            'file_type' => 'File type'
        ];
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ]
        ];
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getVariants() 
    { 
        return $this->hasMany(Variants::className(), ['quize_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\QuizesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\QuizesQuery(get_called_class());
    }
    
    public static function getDateInTimeStamp($date_srt)
    {
        if(is_int($date_srt)){
            return $date_srt;
        }else{
            $date = \DateTime::createFromFormat('d.m.Y H:i', $date_srt);
            return $date->getTimestamp();
        }
    }
    
    public static function getDateInString($date_timestamp)
    {
        $date_timestamp = intval($date_timestamp);
        if(!is_int($date_timestamp)){
            return $date_timestamp;
        }else{
            $date = new \DateTime();
            $date->setTimestamp($date_timestamp);
            return $date->format('d.m.Y H:i');
        }
    }
    
    public static function updateVariants($quize_id, $post)
    {
        foreach (Yii::$app->request->post('Variants') as $key => $post_variant){
            $id = intval($post_variant['id']);
            $variant = new Variants();
            if(is_int($id) && $id>0){
                //Exist variant
                $variant = Variants::find()->where(['id' => $id])->limit(1)->one();
            }
            $variant->text = $post_variant['text'];
            $variant->quize_id = $quize_id;
            $variant->save();
        }
    }
    
    /**
     * Cron task "actionCheckQuizesStatuses"
     */
    public static function refreshAllStatuses() {
        $results = [
            'wait_to_open' => 0,
            'open_to_closed' => 0,
            'open_to_wait' => 0,
        ];
        $now = new \DateTime();
        // Wait to open
        $quizes_to_open = Quizes::find()
                ->where(['status' => 'wait'])
                ->andWhere(['<', 'start_at', $now->getTimestamp()])
                ->andWhere(['>', 'end_at', $now->getTimestamp()])
                ->all();
        foreach ($quizes_to_open as $quize_to_open){
            $quize_to_open->status = 'open';
            $quize_to_open->save();
            $results['wait_to_open']++;
        }
        // Open to closed
        $quizes_to_closed = Quizes::find()
                ->where(['status' => 'open'])
                ->andWhere(['<', 'end_at', $now->getTimestamp()])
                ->all();
        foreach ($quizes_to_closed as $quize_to_closed){
            $quize_to_closed->status = 'closed';
            $quize_to_closed->save();
            $results['open_to_closed']++;
        }
        // Open to wait
        $quizes_to_wait = Quizes::find()
                ->where(['status' => 'open'])
                ->andWhere(['>', 'start_at', $now->getTimestamp()])
                ->all();
        foreach ($quizes_to_wait as $quize_to_wait){
            $quize_to_wait->status = 'wait';
            $quize_to_wait->save();
            $results['open_to_wait']++;
        }
        echo "=== CHANGE QUIZES STAUSES RESULT ===" . PHP_EOL;
        echo "Change wait to open: " . $results['wait_to_open'] . PHP_EOL;
        echo "Change open to closed: " . $results['open_to_closed'] . PHP_EOL;
        echo "Change open to wait: " . $results['open_to_wait'] . PHP_EOL;
    }
}

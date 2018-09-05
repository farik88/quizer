<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "variants".
 *
 * @property integer $id
 * @property string $text
 * @property integer $quize_id
 *
 * @property \common\models\Quizes $quize
 */
class Variants extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'quize'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text', 'quize_id'], 'required'],
            [['quize_id'], 'integer'],
            [['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'variants';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'quize_id' => Yii::t('app', 'Quize ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuize()
    {
        return $this->hasOne(\common\models\Quizes::className(), ['id' => 'quize_id']);
    }
    

    /**
     * @inheritdoc
     * @return \common\models\VariantsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\VariantsQuery(get_called_class());
    }
}

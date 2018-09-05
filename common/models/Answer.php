<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answers".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $quize_id
 * @property integer $variant_id
 *
 * @property Quizes $quize
 * @property User $user
 * @property Variants $variant
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'quize_id', 'variant_id'], 'required'],
            [['user_id', 'quize_id', 'variant_id'], 'integer'],
            [['quize_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quizes::className(), 'targetAttribute' => ['quize_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variants::className(), 'targetAttribute' => ['variant_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'quize_id' => 'Quize ID',
            'variant_id' => 'Variant ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuize()
    {
        return $this->hasOne(Quizes::className(), ['id' => 'quize_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariant()
    {
        return $this->hasOne(Variants::className(), ['id' => 'variant_id']);
    }
}

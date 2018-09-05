<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the base model class for table "social_auth".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $auth_key
 * @property string $network
 * @property string $network_id
 * @property string $url
 *
 * @property \common\models\User $user
 */
class SocialAuth extends ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'user'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'auth_key', 'network', 'network_id'], 'required'],
            [['user_id'], 'integer'],
            [['username', 'network_id', 'url'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['network'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_auth';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'network' => 'Network',
            'network_id' => 'Network ID',
            'url' => 'Url',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
}

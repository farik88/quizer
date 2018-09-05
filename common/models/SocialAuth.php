<?php

namespace common\models;

use Yii;
use \common\models\base\SocialAuth as BaseSocialAuth;

/**
 * This is the model class for table "social_auth".
 */
class SocialAuth extends BaseSocialAuth
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['user_id', 'auth_key', 'network', 'network_id'], 'required'],
            [['user_id'], 'integer'],
            [['username', 'network_id', 'url'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['network'], 'string', 'max' => 60]
        ]);
    }
	
}

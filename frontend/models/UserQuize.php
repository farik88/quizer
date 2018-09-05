<?php

namespace frontend\models;

use \common\models\base\Quizes as BaseQuizes;
use Yii;

class UserQuize extends BaseQuizes{
    
    public $reCaptcha;
    
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
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(),
                'secret' => Yii::$app->components['reCaptcha']['secret'],
                'uncheckedMessage' => 'Пожалуйста, подтвердите что вы не робот'
            ]
        ]);
    }
    
}

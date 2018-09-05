<?php

namespace common\models;

use Yii;
use \common\models\base\Variants as BaseVariants;

/**
 * This is the model class for table "variants".
 */
class Variants extends BaseVariants
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['text', 'quize_id'], 'required'],
            [['quize_id'], 'integer'],
            [['text'], 'string', 'max' => 255]
        ]);
    }
	
}

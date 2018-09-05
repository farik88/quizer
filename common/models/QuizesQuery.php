<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Quizes]].
 *
 * @see Quizes
 */
class QuizesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Quizes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Quizes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

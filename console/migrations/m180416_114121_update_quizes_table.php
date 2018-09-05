<?php

use yii\db\Migration;

/**
 * Class m180416_114121_update_quizes_table
 */
class m180416_114121_update_quizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('quizes', 'file_url', $this->string());
        $this->addColumn('quizes', 'file_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('quizes', 'file_url');
        $this->dropColumn('quizes', 'file_type');
    }
}

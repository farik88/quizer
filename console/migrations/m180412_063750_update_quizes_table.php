<?php

use yii\db\Migration;

/**
 * Class m180412_063750_update_quizes_table
 */
class m180412_063750_update_quizes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('quizes', 'button_color', $this->string()->defaultValue(null));
        $this->addColumn('quizes', 'background_color', $this->string()->defaultValue(null));
        $this->addColumn('quizes', 'logo', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('quizes', 'button_color');
        $this->dropColumn('quizes', 'background_color');
        $this->dropColumn('quizes', 'logo');
    }
}

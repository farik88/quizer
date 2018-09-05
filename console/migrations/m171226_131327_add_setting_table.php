<?php

use yii\db\Migration;

/**
 * Class m171226_131327_add_setting_table
 */
class m171226_131327_add_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('settings',[
            'id' => $this->primaryKey(),
            'key' => $this->string(100)->notNull(),
            'value' => $this->text(),
        ]);
        $this->insert('settings',['key'=>'adminEmail','value'=>'example@email.com']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('settings');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171226_131327_add_setting_table cannot be reverted.\n";

        return false;
    }
    */
}

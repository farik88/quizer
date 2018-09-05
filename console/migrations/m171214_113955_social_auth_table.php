<?php

use yii\db\Migration;

/**
 * Class m171214_113955_social_auth_table
 */
class m171214_113955_social_auth_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%social_auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string(255),
            'auth_key' => $this->string(32)->notNull(),
            'network' => $this->string(60)->notNull(),
            'network_id' => $this->string(255)->notNull(),
            'url' => $this->string()
        ]);
        $this->addForeignKey('social_auth_to_user_key', 'social_auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%social_auth}}');
    }

}

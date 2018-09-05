<?php

use yii\db\Migration;

/**
 * Class m171211_112224_create_quizes_model
 */
class m171211_112224_create_quizes_model extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%quizes}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'status' => $this->string(50)->defaultValue('draft')->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'start_at' => $this->integer()->notNull(),
            'end_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%quizes}}');
    }

}

<?php

use yii\db\Migration;

/**
 * Class m171212_123122_answers_table_create
 */
class m171212_123122_answers_table_create extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%answers}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'quize_id' => $this->integer()->notNull(),
            'variant_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('key_from_answer_to_user', 'answers', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('key_from_answer_to_quize', 'answers', 'quize_id', 'quizes', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('key_from_answer_to_variant', 'answers', 'variant_id', 'variants', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%answers}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m171212_084454_variants_table
 */
class m171212_084454_variants_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%variants}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(255)->notNull(),
            'quize_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('key_to_quize', 'variants', 'quize_id', 'quizes', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%variants}}');
    }

}

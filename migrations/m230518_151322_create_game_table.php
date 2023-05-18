<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game}}`.
 */
class m230518_151322_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'studio_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            '{{%fk-game-studio_id}}',
            '{{%game}}',
            'studio_id',
            '{{%studio}}',
            'id',
        );

        $this->createIndex(
            '{{%idx-game-name}}',
            '{{%game}}',
            'name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-game-name}}', '{{%game}}');
        $this->dropForeignKey('{{%fk-game-studio_id}}', '{{%game}}');
        $this->dropTable('{{%game}}');
    }
}

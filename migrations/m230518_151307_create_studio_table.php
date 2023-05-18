<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%studio}}`.
 */
class m230518_151307_create_studio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%studio}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%studio}}');
    }
}

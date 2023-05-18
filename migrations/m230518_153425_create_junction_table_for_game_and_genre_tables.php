<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%game_genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%game}}`
 * - `{{%genre}}`
 */
class m230518_153425_create_junction_table_for_game_and_genre_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%game_genre}}', [
            'game_id' => $this->integer(),
            'genre_id' => $this->integer(),
            'PRIMARY KEY(game_id, genre_id)',
        ]);

        // creates index for column `game_id`
        $this->createIndex(
            '{{%idx-game_genre-game_id}}',
            '{{%game_genre}}',
            'game_id'
        );

        // add foreign key for table `{{%game}}`
        $this->addForeignKey(
            '{{%fk-game_genre-game_id}}',
            '{{%game_genre}}',
            'game_id',
            '{{%game}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-game_genre-genre_id}}',
            '{{%game_genre}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-game_genre-genre_id}}',
            '{{%game_genre}}',
            'genre_id',
            '{{%genre}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%game}}`
        $this->dropForeignKey(
            '{{%fk-game_genre-game_id}}',
            '{{%game_genre}}'
        );

        // drops index for column `game_id`
        $this->dropIndex(
            '{{%idx-game_genre-game_id}}',
            '{{%game_genre}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-game_genre-genre_id}}',
            '{{%game_genre}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-game_genre-genre_id}}',
            '{{%game_genre}}'
        );

        $this->dropTable('{{%game_genre}}');
    }
}

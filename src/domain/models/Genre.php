<?php

declare(strict_types=1);

namespace game\domain\models;

/**
 * This is the model class for table "genre".
 *
 * @property int $id
 * @property string $genre
 *
 * @property GameGenre[] $gameGenres
 * @property Game[] $games
 */
class Genre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['genre'], 'required'],
            [['genre'], 'string', 'max' => 255],
            [['genre'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'genre' => 'Genre',
        ];
    }

    /**
     * Gets query for [[GameGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameGenres()
    {
        return $this->hasMany(GameGenre::class, ['genre_id' => 'id']);
    }

    /**
     * Gets query for [[Games]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGames()
    {
        return $this->hasMany(Game::class, ['id' => 'game_id'])->viaTable('game_genre', ['genre_id' => 'id']);
    }
}

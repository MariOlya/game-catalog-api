<?php

declare(strict_types=1);

namespace game\domain\models;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property string $name
 * @property int $studio_id
 *
 * @property GameGenre[] $gameGenres
 * @property Genre[] $genres
 * @property Studio $studio
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'studio_id'], 'required'],
            [['studio_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['studio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Studio::class, 'targetAttribute' => ['studio_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'studio_id' => 'Studio ID',
        ];
    }

    /**
     * Gets query for [[GameGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGameGenres()
    {
        return $this->hasMany(GameGenre::class, ['game_id' => 'id']);
    }

    /**
     * Gets query for [[Genres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genre_id'])->viaTable('game_genre', ['game_id' => 'id']);
    }

    /**
     * Gets query for [[Studio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudio()
    {
        return $this->hasOne(Studio::class, ['id' => 'studio_id']);
    }

    public function fields()
    {
        return ['id', 'name'];
    }

    public function extraFields()
    {
        return ['studio', 'genres'];
    }
}

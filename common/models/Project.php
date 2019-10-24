<?php


namespace common\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $project_status_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProjectStatus $projectStatus
 * @property User $user
 */

class Project extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'project_status_id'], 'required'],
            [['user_id', 'project_status_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['project_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::class, 'targetAttribute' => ['project_status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => 'User ID',
            'project_status_id' => 'Project Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProjectStatus()
    {
        return $this->hasOne(ProjectStatus::class, ['id' => 'project_status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
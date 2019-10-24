<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $author_id
 * @property string $executor_id
 * @property int $status_id
 * @property int $priority_id
 * @property TimestampBehavior $created_at
 * @property TimestampBehavior $updated_at
 *
 * @property Comment[] $comments
 * @property Tag[] $tags
 * @property TaskPriority $priority
 * @property TaskStatus $status
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'author_id', 'status_id', 'priority_id'], 'required'],
            [['description'], 'string'],
            [['author_id', 'status_id', 'priority_id'], 'integer'],
            [['name', 'executor_id'], 'string', 'max' => 255],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskPriority::class, 'targetAttribute' => ['priority_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['created_at', 'updated_at'], 'safe']
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
            'author_id' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'executor_id' => 'Executor',
            'description' => 'Description',
            'status_id' => 'Status',
            'priority_id' => 'Priority',
        ];
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    'value' => function () {
                        return date('yyyy-m-d');
                    }
//                    'value' => time(),

                ],
            ],
        ];
    }

    public function task()
    {
        if (!$this->validate()) {
            return null;
        }
        $task = new Task();

        $task->id = $this->id;
        $task->name = $this->name;
        $task->author_id = $this->author_id;
        $task->created_at = $this->created_at;
        $task->updated_at = $this->updated_at;
        $task->description = $this->description;
        $task->executor_id = $this->executor_id;
        $task->status = $this->status;

        return $task->save();
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(TaskPriority::class, ['id' => 'priority_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::class, ['id' => 'status_id']);
    }

    public function fields()
    {
        $parentFields =  parent::fields();
        $modelFields = [
            'created_at'=> function() {
                if (isset($this->created_at)){
                    return Yii::$app->formatter->asDatetime($this->created_at);
                }

                return null;
            },
            'priority_id' => function () {
                return TaskPriority::getPriorityName()[$this->priority_id];
            },
            'status_id' => function () {
                return $this->status->name;
            }
        ];

        return array_merge($parentFields, $modelFields);
    }
}
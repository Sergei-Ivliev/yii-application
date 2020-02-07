<?php

namespace common\models;

use common\components\behaviors\ChatLogBehavior;
use common\components\interfaces\ChatLoggable;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;


/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $author_id
 * @property int $executor_id
 * @property int $status_id
 * @property int $priority_id
 * @property int $accountable_id
 * @property int $project_id
 * @property TimestampBehavior $created_at
 * @property TimestampBehavior $updated_at
 *
 * @property Comment[] $comments
 * @property Tag[] $tags
 * @property TaskPriority $priority
 * @property TaskStatus $status
 * @property User $author
 * @property User $executor
 * @property User $accountable
 * @property Project $project
 */
class Task extends ActiveRecord implements Linkable, ChatLoggable
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
            ['author_id', 'default', 'value' => Yii::$app->user->id],
            [['name', 'description', 'author_id', 'status_id', 'priority_id'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['author_id', 'status_id', 'priority_id', 'project_id', 'executor_id', 'accountable_id'], 'integer'],
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
            'author_id' => 'Author ID',
            'executor_id' => 'Executor ID',
            'description' => 'Description',
            'status_id' => 'Status ID',
            'priority_id' => 'Priority ID',
            'project_id' => 'Project ID',
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
                    'value' => time(),
                ],
            ],
            'chatLogBehavior' => [
                'class' => ChatLogBehavior::class,
            ],
            'authorBehavior' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => false,
                'value' => Yii::$app->user->id
            ],
        ];
    }

    public function beforeValidate()
    {
        if (Yii::$app->request->isPost) {
            $this->status_id = TaskStatus::IN_PROGRESS_ID;
            $this->priority_id = TaskPriority::NORMAL_ID;
        }

        return parent::beforeValidate();
    }

    public function AfterSave($insert, $changedAttribute)
    {
        parent::afterSave($insert, $changedAttribute);
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
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAccountable()
    {
        return $this->hasOne(User::class, ['id' => 'accoutable_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::class, ['id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
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
            },
            'someRandomName' => function () {
                return rand(0, 100000);
            }
        ];

        return array_merge($parentFields, $modelFields);
    }


    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['task/view', 'id' => $this->id], true),
            'author_link' => Url::to(['user/view', 'id' => $this->author_id], true),
        ];
    }

    public function extraFields()
    {
        return [
            'author' => function () {
               return $this->author;
            }
        ];
    }

    public static function findOne($condition)
    {
        if (Yii::$app->cache->exists(self::tableName() . '_' . $condition) === false) {
            return parent::findOne($condition);
        } else {
            return Yii::$app->cache->get(self::tableName() . '_' . $condition);
        }
    }

    public function saveChatLog()
    {
        $chatLog = new ChatLog();

        $message = "Пользователь {$this->author->username} создал новую задачу {$this->name}";
        $chatLog->task_id = $this->id;
        $chatLog->project_id = $this->project_id;
        $chatLog->type = ChatLog::TYPE_CHAT_MESSAGE;
        $chatLog->username = \Yii::$app->user->identity->username;
        $chatLog->message = $message;
        $chatLog->save();
    }
}
<?php


namespace common\models;


use common\components\behaviors\ChatLogBehavior;
use common\components\interfaces\ChatLoggable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property int $author_id
 * @property int $project_status_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProjectStatus $projectStatus
 * @property User $author
 */
class Project extends ActiveRecord implements ChatLoggable
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
            [['name', 'author_id', 'project_status_id'], 'required'],
            [['author_id', 'project_status_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['project_status_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => ProjectStatus::class,
                'targetAttribute' => ['project_status_id' => 'id']
            ],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
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
            'project_status_id' => 'Project Status ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate()
    {
        $this->author_id = Yii::$app->user->id;
        return parent::beforeValidate();
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
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public static function getActiveProjects(): array
    {
        return self::find()->where('project_status_id != :finished_status_id', [":finished_status_id" => ProjectStatus::FINISHED_ID])->all();

    }

    public function saveChatLog()
    {
        $chatLog = new ChatLog();
        $message = "Пользователь {$this->author->username} создал новый проект {$this->name}";
        $chatLog->project_id = $this->id;
        $chatLog->type = ChatLog::TYPE_CHAT_MESSAGE;
        $chatLog->username = \Yii::$app->user->identity->username;
        $chatLog->message = $message;
        $chatLog->save();
    }
}
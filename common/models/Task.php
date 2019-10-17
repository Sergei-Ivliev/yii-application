<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $name
 * @property string $creator
 * @property string $created_at
 * @property string $updated_at
 * @property string $deadline
 * @property string $completed
 * @property int $status
 */
class Task extends ActiveRecord implements ActiveRecordInterface
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
            [['name', 'creator', 'created_at', 'deadline', 'completed'], 'required'],
            [['created_at', 'updated_at', 'deadline', 'completed'], 'safe'],
            [['status'], 'boolean'],
            [['name', 'creator'], 'string', 'max' => 32],
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
            'creator' => 'Creator',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deadline' => 'Deadline',
            'completed' => 'Completed',
            'status' => 'Status',
        ];
    }
}

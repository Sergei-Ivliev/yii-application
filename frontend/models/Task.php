<?php


namespace frontend\models;


use common\models\Task;

class TaskForm extends Task
{
//    public $id;
//    public $name;
//    public $creator;
//    public $created_at;
//    public $updated_at;
//    public $deadline;
//    public $status;


    public function createTask()
    {
        if (!$this->validate()) {
            return null;
        }
        $task = new Task();
        $task->name = $this->name;
        $task->creator = $this->creator;
        $task->created_at = $this->created_at;
        $task->updated_at = $this->updated_at;
        $task->deadline = $this->deadline;
        $task->status = $this->status;

        return $task->save();
    }


}
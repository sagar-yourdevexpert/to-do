<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function getAllTasks()
    {
        return Task::all();
    }

    public function createTask(Request $request)
    {
        return Task::create([
            'title' => $request->title,
        ]);
    }

    public function updateTask(Request $request, Task $task)
    {
        $task->update([
            'title' => $request->title,
        ]);
        return $task;
    }
}
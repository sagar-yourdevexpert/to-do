<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;


class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return $this->taskService->getAllTasks();
    }

    public function store(Request $request)
    {
        return $this->taskService->createTask($request);
    }

    public function update(Request $request, Task $task)
    {
        return $this->taskService->updateTask($request, $task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
}

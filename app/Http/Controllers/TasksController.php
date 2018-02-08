<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Session;
use Validator;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks/task', compact('tasks'));
    }

    public function newTask(Request $request)
    {
        $this->validate($request, [
            'task' => 'required | min: 3 | max: 10 | alpha_num | unique:tasks,title',
        ]);
        $newTask        = new Task();
        $newTask->title = $request->task;
        $newTask->save();
        Session::flash('status', 'Task was successfully added');
        return redirect('/');
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect('/');
    }

    public function updateTask(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'updatedTask' => 'required | min: 3 | max: 10 | alpha_num | unique:tasks,title',
        ]);
        if ($validator->passes()) {
            $updatedTask        = Task::find($id);
            $updatedTask->title = $request->updatedTask;
            $updatedTask->save();
            return response()->json(['success' => 'Task updated']);
        }
        return response()->json(['error' => $validator->errors()->all()]);

    }
}

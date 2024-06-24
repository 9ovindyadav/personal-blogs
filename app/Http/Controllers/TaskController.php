<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tasks = Task::where('assigned_to','=', $user->id)->get();

        return view('task.user-task',['tasks' => $tasks, 'pageTitle' => 'All Tasks']);
    }

    public function create()
    {
        $users = User::select('id','name')->get()->pluck('name','id')->toArray();
        $users = array_merge([0 => ''], $users);
        return view('task.form',['formTitle' => 'Add New Task', 'users' => $users]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'subject' => ['required','min:5','max:30'],
            'assigned_to' => ['required','int']
        ]);

        $user = auth()->user();
        $attributes['created_by'] = $user->id;

        if(request()->input('project_id')){
            $project = Project::find(request()->input('project_id'));
            $project->relation('task','M:M')->create($attributes);
        }else{
            Task::create($attributes);
        }  

        return redirect("/tasks")->with('status',"Task created successfully");
    }

    public function edit(Task $task)
    {
        $users = User::select('id','name')->get()->pluck('name','id')->toArray();
        $users = array_merge([0 => ''], $users);
        return view('task.form',['formTitle' => 'Edit Task', 'users' => $users, 'task' => $task]);
    }

    public function update()
    {
        $attributes = request()->validate([
            'subject' => ['required','min:5','max:30'],
            'assigned_to' => ['required','int'],
            'project_id' => ['int'],
            'task_id' => ['required','int'],
        ]);

        $task = Task::find($attributes['task_id']);
        $task->relation('project','M:M',true)->detach();
       
        if(isset($attributes['project_id'])){
            $task->relation('project','M:M',true)->attach($attributes['project_id']);
        }

        unset($attributes['task_id'],$attributes['project_id']);
        $task->update($attributes);

        return redirect('/tasks')->with('status','Task updated successfully');
    }

    public function delete(Task $task)
    {
        $task->relation('project','M:M',true)->detach($task);
        $task->delete();

        return redirect('/tasks')->with('status','Task deleted successfully');
    }
}

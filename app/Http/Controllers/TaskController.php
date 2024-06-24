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

        return view('task.user-task',['tasks' => $tasks]);
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

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = $user->relation('project','M:M')->get();

        return view('project.user-project',['projects' => $projects, 'pageTitle' => 'All Projects']);
    }

    public function create()
    {
        $users = User::select('id','name')->get()->pluck('name','id')->toArray();
        
        return view('project.form',['formTitle' => 'Add New Project', 'users' => $users]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required','min:5','max:30'],
            'assigned_to' => ['int']
        ]);
        
        $user = auth()->user();

        $user->relation('project','M:M')->create(['created_by' => $user->id, ...$attributes]);

        return redirect("/profile/{$user->username}")->with('status',"{$attributes['name']} project created successfully");
    }

    public function edit(Project $project)
    {
        $users = User::select('id','name')->get()->pluck('name','id')->toArray();

        return view('project.form',
                    [
                        'formTitle' => 'Edit Project', 
                        'project' => $project,
                        'users' => $users
                    ]
                );
    }

    public function update()
    {
        $attributes = request()->validate([
            'name' => ['required','min:5','max:30'],
            'assigned_to' => ['int'],
            'project_id' => ['int']
        ]);

        $project = Project::find($attributes['project_id']);
        unset($attributes['project_id']);
    
        $project->update($attributes);

        $user = auth()->user();
        return redirect("/profile/{$user->username}")->with('status',"{$attributes['name']} project updated successfully");
    }

    public function delete(Project $project)
    {
        $user = auth()->user();

        $user->relation('project','M:M')->detach($project);
        $project->delete();

        return redirect("/profile/{$user->username}")->with('status',"{$project->name} project deleted successfully");
    }

    public function tasks(Project $project)
    {
        $tasks = $project->relation('task','M:M')->get();

        return view('task.user-task',['tasks' => $tasks, 'pageTitle' => $project->name.' Tasks  ']);
    }
}

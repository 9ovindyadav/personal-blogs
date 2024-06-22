<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
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

        $user->relation('project')->create(['created_by' => $user->id, ...$attributes]);

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

        $user->relation('project')->detach($project);
        $project->delete();

        return redirect("/profile/{$user->username}")->with('status',"{$project->name} project deleted successfully");
    }
}

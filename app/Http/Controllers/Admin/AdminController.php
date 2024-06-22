<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class AdminController extends Controller
{
    public function index()
    {        
        return view('admin.dashboard');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function projects()
    {
        $projects = Project::all();

        return view('project.list',['projects' => $projects]);
    }

    public function tasks()
    {
        $tasks = Task::all();

        return view('task.list',['tasks' => $tasks]);
    }
}

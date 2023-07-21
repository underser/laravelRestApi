<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\Store;
use App\Http\Requests\Projects\Update;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::query()->withAll();
        $perPage = request()?->query('perPage');

        $projects = ($perPage ? $projects->paginate($perPage) : $projects->paginate())->withQueryString();

        return view('crm.admin.projects.index', [
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('crm.admin.projects.create', [
            'clients' => Client::all(),
            'users' => User::all(),
            'statuses' => ProjectStatus::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request)
    {
        return to_route('projects.show', Project::factory()->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('crm.admin.projects.show', [
            'project' => $project->loadMissing(['user', 'client', 'status']),
            'clients' => Client::all(),
            'users' => User::all(),
            'statuses' => ProjectStatus::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('crm.admin.projects.edit', [
            'project' => $project->loadMissing(['user', 'client', 'status']),
            'clients' => Client::all(),
            'users' => User::all(),
            'statuses' => ProjectStatus::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Project $project)
    {
        $project->update($request->validated());
        return to_route('projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('projects.index');
    }
}
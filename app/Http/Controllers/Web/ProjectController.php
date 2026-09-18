<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function index(Request $request): View
    {
        $projects = $this->projectService->listForUser(
            $request->user()
        );

        return view('projects.index', compact('projects'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Project::class);

        $users = $this->userRepository->getAllExcept(
            $request->user()
        );

        return view('projects.create', compact('users'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->projectService->create(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project = $this->projectService->getForView($project);

        return view('projects.show', compact('project'));
    }

    public function edit(
        Request $request,
        Project $project
    ): View {
        $this->authorize('update', $project);

        $users = $this->userRepository->getAllExcept(
            $request->user()
        );

        $project->load('users:id,name,email');

        return view('projects.edit', compact(
            'project',
            'users'
        ));
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project
    ): RedirectResponse {
        $this->authorize('update', $project);

        $project = $this->projectService->update(
            $project,
            $request->validated()
        );

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $this->projectService->delete($project);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProjectMemberRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $projects = $this->projectService->listForUser(
            $request->user(),
            $request->integer('per_page', 15)
        );

        return ProjectResource::collection($projects);
    }

    public function store(
        StoreProjectRequest $request
    ): ProjectResource {
        $this->authorize(
            'create',
            Project::class
        );

        $project = $this->projectService->create(
            $request->user(),
            $request->validated()
        );

        return new ProjectResource(
            $project->load([
                'owner:id,name,email',
                'users:id,name,email',
            ])
        );
    }

    public function show(
        Project $project
    ): ProjectResource {
        $this->authorize(
            'view',
            $project
        );

        $project = $this->projectService->getForView(
            $project
        );

        return new ProjectResource($project);
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project
    ): ProjectResource {
        $this->authorize(
            'update',
            $project
        );

        $project = $this->projectService->update(
            $project,
            $request->validated()
        );

        return new ProjectResource(
            $project->load([
                'owner:id,name,email',
                'users:id,name,email',
            ])
        );
    }

    public function destroy(
        Project $project
    ): JsonResponse {
        $this->authorize(
            'delete',
            $project
        );

        $this->projectService->delete(
            $project
        );

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.',
            'data' => null,
        ]);
    }

    public function addMember(
        AddProjectMemberRequest $request,
        Project $project
    ): ProjectResource {
        $this->authorize(
            'addMember',
            $project
        );

        $data = $request->validated();

        $user = User::findOrFail(
            $data['user_id']
        );

        $this->projectService->addMember(
            $project,
            $user,
            $data['role'] ?? 'member'
        );

        return new ProjectResource(
            $project->fresh([
                'owner:id,name,email',
                'users:id,name,email',
            ])
        );
    }

    public function removeMember(
        Project $project,
        User $user
    ): ProjectResource {
        $this->authorize(
            'removeMember',
            $project
        );

        $this->projectService->removeMember(
            $project,
            $user
        );

        return new ProjectResource(
            $project->fresh([
                'owner:id,name,email',
                'users:id,name,email',
            ])
        );
    }
}
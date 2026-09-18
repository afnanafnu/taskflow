<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'description' => $this->description,

            'status' => $this->status,

            'priority' => $this->priority,

            'due_date' => $this->due_date?->toDateString(),

            'project' => $this->when(
                $this->relationLoaded('project'),
                fn () => [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                ]
            ),

            'assignee' => $this->when(
                $this->relationLoaded('assignee'),
                fn () => $this->assignee
                    ? new UserResource($this->assignee)
                    : null
            ),

            'labels' => $this->when(
                $this->relationLoaded('labels'),
                fn () => LabelResource::collection(
                    $this->labels
                )
            ),

            'comments_count' => $this->when(
                isset($this->comments_count),
                $this->comments_count
            ),

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
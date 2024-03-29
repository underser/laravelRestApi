<?php

namespace App\Http\Resources\V1;

use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property User $user
 * @property User $client
 * @property ProjectStatus $status
 * @property Carbon $deadline
 */
class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->whenLoaded('user', fn() => $this->user->name),
            'client' => $this->whenLoaded('client', fn() => $this->client->name),
            'project_status' => $this->whenLoaded('status', fn() => $this->status->status),
            'deadline' => $this->deadline->format('m/d/Y'),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'tasks_count' => $this->whenCounted('tasks'),
        ];
    }
}

<?php

namespace App\Modules\Course\Http\Resources\Review;

use Illuminate\Http\Request;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Resources\Content\ContentResource;

class ReviewResource extends JsonResource
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
            'teacher_id' => $this->teacher_id,
            'follow_up' => $this->follow_up,
            'degree_focus' => $this->degree_focus,
            'interacting_tasks' => $this->interacting_tasks,
            'behavior_cooperation' => $this->behavior_cooperation,
            'progress_understanding' => $this->progress_understanding,
            'notes' => $this->notes,
            'user' => $this->user(),
            'content' => new ContentResource($this->content),
        ];
    }
}

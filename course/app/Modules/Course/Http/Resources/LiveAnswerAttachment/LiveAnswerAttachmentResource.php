<?php

namespace App\Modules\Course\Http\Resources\LiveAnswerAttachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Group\GroupResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\CourseTitleResource;

class LiveAnswerAttachmentResource extends JsonResource
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
            'attachment' => $this->media_link ?? null,
            'type' => $this->type ?? null,
            'alt' => $this->alt ?? null,
        ];
    }
}

<?php

namespace App\Modules\Course\Http\Resources\LiveAnswer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Group\GroupResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\CourseTitleResource;
use App\Modules\Course\Http\Resources\LiveAnswerAttachment\LiveAnswerAttachmentResource;

class LiveAnswerResource extends JsonResource
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
            "question_id" => $this->live_question_id ,
            "answer" => $this->answer ?? null,
            "is_correct" => boolval($this->is_correct) ?? false,
            "attachments"   => isset($this->attachments) ? LiveAnswerAttachmentResource::collection($this->attachments) : [],
        ];
    }
}

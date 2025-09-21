<?php

namespace App\Modules\Course\Http\Resources\LiveQuestion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Group\GroupResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\CourseTitleResource;
use App\Modules\Course\Http\Resources\LiveAnswer\LiveAnswerResource;
use App\Modules\Course\Http\Resources\LiveQuestionAttachment\LiveQuestionAttachmentResource;

class LiveQuestionResource extends JsonResource
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
            // 'parent_id' => $this->parent_id ?? null,
            "question_type" => $this->question_type ?? null,
            "identicality" => $this->identicality ?? null,
            "identicality_percentage" => $this->identicality_percentage ?? null,
            "difficulty" => $this->difficulty ?? null,
            "difficulty_level" => $this->difficulty_level ?? null,
            "question" => $this->question ?? null,
            "degree" => $this->degree ?? null,
            "time" => $this->time ?? null,
            "creator" => $this->creator ?? null,
            'created_at' => date_format($this->created_at, 'Y-m-d H:i:s') ?? null,
            'updated_at' => date_format($this->updated_at, 'Y-m-d H:i:s') ?? null,
            "attachments" => isset($this->attachments) ? LiveQuestionAttachmentResource::collection($this->attachments) : [],
            "answers"   => isset($this->answers) ? LiveAnswerResource::collection($this->answers) : [],
        ];
    }
}

<?php

namespace App\Modules\Course\Http\Controllers\Api\Lesson;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Lesson\LessonUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Lesson\FetchLessonRequest;

class LessonController extends Controller
{
    protected $lessonUseCase;

    public function __construct(LessonUseCase $lessonUseCase)
    {
        $this->lessonUseCase = $lessonUseCase;
    }

    public function FetchLessons(FetchLessonRequest $request)
    {
        return $this->lessonUseCase->fetchLessons($request->toDTO())->response();
    }
}

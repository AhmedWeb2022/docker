<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Review;


use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Requests\Teacher\Review\FetchReviewRequest;
use App\Modules\Course\Http\Requests\Teacher\Review\CreateReviewRequest;
use App\Modules\Course\Application\UseCases\Teacher\Review\ReviewUseCase;
use App\Modules\Course\Http\Requests\Teacher\Review\FetchReviewDetailsRequest;

class ReviewController extends Controller
{
    protected $reviewUseCase;
    protected $teacher;

    public function __construct(ReviewUseCase $reviewUseCase)
    {
        $this->reviewUseCase = $reviewUseCase;
        $this->teacher = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::TEACHER->value);
    }

    public function createReview(CreateReviewRequest $request)
    {
        $reviewDTO = $request->toDTO();
        $reviewDTO->teacher_id = $this->teacher->id;
        return $this->reviewUseCase->createReview($reviewDTO)->response();
    }

    public function fetchReviewDetails(FetchReviewDetailsRequest $request)
    {
        $reviewDTO = $request->toDTO();
        $reviewDTO->teacher_id = $this->teacher->id;
        return $this->reviewUseCase->fetchReviewDetails($reviewDTO)->response();
    }

    public function fetchReviews(FetchReviewRequest $request)
    {
        $reviewDTO = $request->toDTO();
        $reviewDTO->teacher_id = $this->teacher->id;
        return $this->reviewUseCase->fetchReviews($reviewDTO)->response();
    }

    public function deleteReview(FetchReviewDetailsRequest $request)
    {
        $reviewDTO = $request->toDTO();
        $reviewDTO->teacher_id = $this->teacher->id;
        return $this->reviewUseCase->deleteReview($reviewDTO)->response();
    }
}

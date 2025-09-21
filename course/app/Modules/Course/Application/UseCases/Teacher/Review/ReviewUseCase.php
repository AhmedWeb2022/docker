<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Review;


use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Http\Resources\Review\ReviewResource;
use App\Modules\Course\Application\DTOS\Teacher\Review\ReviewDTO;
use App\Modules\Course\Http\Resources\Review\Api\ReviewDetailsFullResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Review\ReviewRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\ReviewUser\ReviewUserRepository;

class ReviewUseCase
{

    protected $reviewRepository;


    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function fetchReviews(ReviewDTO $reviewDTO): DataStatus
    {
        try {
            $reviews = $this->reviewRepository->filter(
                dto: $reviewDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $reviewDTO->paginate,
                limit: $reviewDTO->limit
            );
            return DataSuccess(
                status: true,
                message: 'Reviews fetched successfully',
                data: $reviews
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchReviewDetails(ReviewDTO $reviewDTO): DataStatus
    {
        try {
            // dd($reviewDTO);
            $review = $this->reviewRepository->getMultibleWhere([
                'id' => $reviewDTO->review_id,
                'teacher_id' => $reviewDTO->teacher_id
            ], 'first');
            return DataSuccess(
                status: true,
                message: 'Review fetched successfully',
                data: $review ? new ReviewResource($review) : null
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createReview(ReviewDTO $reviewDTO): DataStatus
    {
        try {
            // dd($reviewDTO);
            $review = $this->reviewRepository->create($reviewDTO);

            return DataSuccess(
                status: true,
                message: 'Review created successfully',
                data: new ReviewResource($review)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteReview(ReviewDTO $reviewDTO): DataStatus
    {
        try {
            $review = $this->reviewRepository->getMultibleWhere([
                'id' => $reviewDTO->review_id,
                'teacher_id' => $reviewDTO->teacher_id
            ], 'first');

            if (!$review) {
                return DataFailed(
                    status: false,
                    message: 'Review not found'
                );
            }

            $this->reviewRepository->delete($review->id);

            return DataSuccess(
                status: true,
                message: 'Review deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}

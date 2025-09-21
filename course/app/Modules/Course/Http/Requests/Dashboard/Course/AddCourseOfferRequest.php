<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use Carbon\Carbon;
use Illuminate\Validation\Validator;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Course\CourseOfferDTO;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseOffer\CourseOffer;

class AddCourseOfferRequest extends BaseRequestAbstract
{
    protected $dtoClass = CourseOfferDTO::class;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function customRules(): array
    {
        return [
            'course_id' => 'required|integer|exists:courses,id',
            'discount_amount' => 'required|min:0|numeric',
            'discount_from_date' => 'required|date|date_format:Y-m-d|before_or_equal:discount_to_date',
            'discount_to_date'   => 'required|date|date_format:Y-m-d|after_or_equal:discount_from_date',
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $now = Carbon::today();

            // Rule 1: Must start from now or future
            if ($this->discount_from_date && Carbon::parse($this->discount_from_date)->lt($now)) {
                $validator->errors()->add('discount_from_date', 'The discount start date must be today or in the future.');
            }

            // Rule 2: Overlap check
            if ($this->discount_from_date && $this->discount_to_date) {
                $overlapExists = CourseOffer::where('course_id', $this->course_id)
                    ->where(function ($query) {
                        $query
                            ->whereBetween('discount_from_date', [$this->discount_from_date, $this->discount_to_date])
                            ->orWhereBetween('discount_to_date', [$this->discount_from_date, $this->discount_to_date])
                            ->orWhere(function ($query) {
                                $query->where('discount_from_date', '<=', $this->discount_from_date)
                                    ->where('discount_to_date', '>=', $this->discount_to_date);
                            });
                    })
                    ->exists();

                if ($overlapExists) {
                    $validator->errors()->add('discount_from_date', 'The discount period overlaps with an existing discount for this course.');
                }
            }
        });
    }
}

<?php

namespace App\Modules\Diploma\Http\Controllers\Website\Faq;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Diploma\Application\UseCases\Faq\FaqUseCase;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FetchFaqRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\CreateFaqRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\UpdateFaqRequest;

// use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;
// use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;




class FaqController extends Controller
{
    protected $faqUseCase;

    public function __construct(FaqUseCase $faqUseCase)
    {
        $this->faqUseCase = $faqUseCase;
    }

    public function fetchFaqs(FetchFaqRequest $request)
    {
        return $this->faqUseCase->fetchFaqs($request->toDTO() , ViewTypeEnum::MOBILE->value)->response();
    }


}

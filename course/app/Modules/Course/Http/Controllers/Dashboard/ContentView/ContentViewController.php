<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\ContentView;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\ContentView\ContentViewUseCase;
use App\Modules\Course\Http\Requests\Global\ContentView\ContentViewIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\ContentView\FetchContentViewRequest;
use App\Modules\Course\Http\Requests\Dashboard\ContentView\CreateContentViewRequest;
use App\Modules\Course\Http\Requests\Dashboard\ContentView\UpdateContentViewRequest;
use App\Modules\Course\Http\Requests\Dashboard\ContentView\HandelMultibleUsersRequest;

class ContentViewController extends Controller
{
    protected $contentViewUseCase;

    public function __construct(ContentViewUseCase $contentViewUseCase)
    {
        $this->contentViewUseCase = $contentViewUseCase;
    }


}

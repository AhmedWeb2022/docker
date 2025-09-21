<?php

namespace App\Modules\Notification\Http\Controllers\Topic\Api;


use App\Http\Controllers\Controller;
use App\Modules\Notification\Application\UseCases\Topic\TopicUseCase;

class TopicController extends Controller
{
    protected $TopicUseCase;

    public function __construct(TopicUseCase $TopicUseCase)
    {
        $this->TopicUseCase = $TopicUseCase;
    }
}

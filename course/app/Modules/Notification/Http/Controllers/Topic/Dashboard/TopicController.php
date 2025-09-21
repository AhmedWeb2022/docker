<?php

namespace App\Modules\Notification\Http\Controllers\Topic\Dashboard;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Notification\Application\UseCases\Topic\TopicUseCase;
use App\Modules\Notification\Http\Requests\Topic\Global\TopicIdRequest;
use App\Modules\Notification\Http\Requests\Topic\Dashboard\FetchTopicRequest;
use App\Modules\Notification\Http\Requests\Topic\Dashboard\CreateTopicRequest;
use App\Modules\Notification\Http\Requests\Topic\Dashboard\UpdateTopicRequest;

class TopicController extends Controller
{
    protected $TopicUseCase;

    public function __construct(TopicUseCase $TopicUseCase)
    {
        $this->TopicUseCase = $TopicUseCase;
    }


    public function fetchTopics(FetchTopicRequest $request)
    {
        return $this->TopicUseCase->fetchTopics($request->toDTO())->response();
    }

    public function createTopic(CreateTopicRequest $request)
    {
        return $this->TopicUseCase->createTopic($request->toDTO())->response();
    }

    public function updateTopic(UpdateTopicRequest $request)
    {
        return $this->TopicUseCase->updateTopic($request->toDTO())->response();
    }

    public function deleteTopic(TopicIdRequest $request)
    {
        return $this->TopicUseCase->deleteTopic($request->toDTO())->response();
    }
}

<?php

namespace App\Modules\Course\Application\DTOS\ContentView;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ContentViewDTO extends BaseDTOAbstract
{
    public $content_view_id;
    public $content_id;
    public $user_id;
    public $stops;
    public $is_finished;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function uniqueAttributes(): array
    {
        return [
            'user_id',
            'content_id',
        ]; // Default empty array
    }
}

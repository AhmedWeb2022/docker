<?php

namespace App\Modules\Course\Application\DTOS\LiveQuestion;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class LiveQuestionDTO extends BaseDTOAbstract
{
    public $live_question_id;
    public $organization_id;
    public $content_id;
    public $parent_id;
    public $question_type;
    public $identicality;
    public $identicality_percentage;
    public $difficulty;
    public $difficulity;
    public $difficulty_level;
    public $question;
    public $degree;
    public $time;
    public $creator;
    public $answers;
    public $attachments;
    protected string $imageFolder = 'live_question';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        // Handle any special cases for this DTO here
        if (isset($this->difficulity) && is_numeric($this->difficulity)) {
            $this->difficulty = $this->difficulity; // Ensure correct spelling
        }
    }
    public function excludedAttributes(): array
    {
        return [
            'difficulity'
        ]; // Default empty array
    }
}

<?php
namespace App\Modules\Website\Application\DTOS\WebsiteSectionContent;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
class WebsiteSectionContentDTO extends BaseDTOAbstract
{
    // protected bool $excludeAttributes = true;
    protected bool $hasMorph = true; // Default is false
    protected array $morphMap = [
        'course_id' => 'App\Modules\Course\Infrastructure\Persistence\Models\Course',
        'diploma_id' => 'App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma',
    ];
    public $id;
    public $website_section_id;
    public $contentable_id;
    public $contentable_type;

    public $course_id;
    public $diploma_id;
    public $created_by;
    public $updated_by;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function getMorphFields($data)
    {
        if ($this->morphMap == []) {
            throw new \Exception("Morph map is not defined in " . get_class($this));
        } else {
            foreach ($data as $key => $value) {
                if (isset($this->morphMap[$key])) {
                    $this->contentable_id = $value;
                    $this->contentable_type = $this->morphMap[$key];

                    return [
                        'contentable_id' => $this->contentable_id,
                        'contentable_type' => $this->contentable_type,
                    ];
                }
            }
        }
        return [];
    }
}

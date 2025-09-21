<?php

namespace App\Modules\Course\Http\Resources\Reference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Audio\FullAudioResource;
use App\Modules\Course\Http\Resources\Document\DocumentResource;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Http\Resources\Session\FullSessionResource;
use App\Modules\Course\Http\Resources\Document\FullDocumentResource;

class ReferanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            'link' => $this->link,
            'image' => $this->image_link,
        ];

        return $data;
    }
}

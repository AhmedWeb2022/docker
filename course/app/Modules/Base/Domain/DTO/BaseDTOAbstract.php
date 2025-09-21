<?php

namespace App\Modules\Base\Domain\DTO;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use ReflectionClass;
use ReflectionProperty;

abstract class  BaseDTOAbstract implements BaseDTOInterface
{
    protected $email = null;
    protected $phone = null;
    protected $translations = null;
    protected $new_password = null;
    protected bool $excludeAttributes = true; // Default is false
    protected bool $hasMorph = false; // Default is false
    protected array $morphMap = [];
    public $paginate;
    public $limit;
    public ?string $orderBy = 'id'; // Default order by
    public ?string $direction = 'asc'; // Default order direction
    protected string $imageFolder = 'default'; // fallback folder

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }


    public static function fromArray(array $data): self
    {
        // dd($data);
        $dto = new static();
        // Handle credential if present
        if (isset($data['credential'])) {
            $credential = $data['credential'];
            if (property_exists($dto, 'email') && filter_var($credential, FILTER_VALIDATE_EMAIL)) {
                $dto->email = $credential;
            } elseif (property_exists($dto, 'phone')) {
                $dto->phone = $credential;
            }
        }
        if ($dto->hasMorph && method_exists($dto, 'getMorphFields')) {
            $dto->getMorphFields($data);
        }
        foreach ($data as $key => $value) {
            if (property_exists($dto, $key)) {
                // Skip recursion for "translations"


                // If the value is an array (assumed object), process it recursively
                if (is_array($value) && !in_array($key, ['translations'])) {
                    foreach ($value as $subKey => $subValue) {
                        if (($subValue instanceof UploadedFile && isImage($subValue)) || (is_string($subValue) && isBase64($subValue))) {
                            // Upload the image or base64-encoded file
                            $folder = property_exists($dto, 'imageFolder') ? $dto->imageFolder : 'default';
                            $value[$subKey] = uploadImage($subValue, $subKey, $folder);
                        }
                    }
                    $dto->{$key} = $value;
                } elseif (($value instanceof UploadedFile && isImage($value)) || (is_string($value) && isBase64($value))) {
                    $folder = property_exists($dto, 'imageFolder') ? $dto->imageFolder : 'default';
                    $dto->{$key} = uploadImage($value, $key, $folder);
                } else {
                    $dto->{$key} = $value;
                }
            }
        }
        if (method_exists($dto, 'handleSpecialCases')) {
            $dto->handleSpecialCases();
        }
        return $dto;
    }
    /**
     * Convert an array to a DTO object
     */







    public function toArray(array $excludedAttributes = []): array
    {
        $data = get_object_vars($this);

        // If `excludeAttributes` is true, call `excludedAttributes()`
        if ($this->excludeAttributes && method_exists($this, 'excludedAttributes')) {
            $excludedAttributes = array_merge($excludedAttributes, $this->execlusion());
        }
        // dd($excludedAttributes);
        // Handle password conversion
        if (isset($this->new_password)) {
            $data['password'] = $this->new_password;
            unset($data['old_password']);
            unset($data['new_password']);
        }
        if (isset($this->translations) && count($this->translations) > 0) {
            $data = array_merge($data, $this->getTranslations());
            unset($data['translations']);
        }
        // Remove excluded attributes
        foreach ($excludedAttributes as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            } else {
                Log::warning("Attempted to exclude missing key: {$key}");
            }
        }
        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * Get translations
     * install mcamara/laravel-localization
     * install astrotomic/laravel-translatable
     * "en" => [
     *     "name" => "Name",
     *     "description" => "Description",
     * ],
     * "ar" => [
     *     "name" => "اسم",
     *     "description" => "وصف",
     * ],
     */
    public function getTranslations(): array
    {
        $translations = [];

        if (isset($this->translations)) {
            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $localeFields = array_filter($this->translations, function ($key) use ($localeCode) {
                    return str_ends_with($key, "_{$localeCode}");
                }, ARRAY_FILTER_USE_KEY);

                $formattedLocaleFields = [];
                foreach ($localeFields as $key => $value) {
                    $newKey = str_replace("_{$localeCode}", '', $key);
                    $formattedLocaleFields[$newKey] = $value;
                }

                $translations[$localeCode] = $formattedLocaleFields;
            }
        }

        return $translations;
    }
    private function baseExcludedAttributes(): array
    {
        return [
            'excludeAttributes',
            'hasMorph',
            'translations',
            'new_password',
            'paginate',
            'limit',
            'imageFolder',
            // 'with_pagination',
            'orderBy',
            'direction',
            'morphMap',
        ];
    }

    private function execlusion()
    {
        return array_merge(
            $this->baseExcludedAttributes(),
            $this->excludedAttributes()
        );
    }

    // Define an abstract method to enforce its implementation in child classes
    public function excludedAttributes(): array
    {
        return []; // Default empty array
    }
    public function getExcludedArray(): array
    {
        $excludedData = [];

        if ($this->excludeAttributes && method_exists($this, 'excludedAttributes')) {
            $excludedAttributes = $this->excludedAttributes();

            foreach ($excludedAttributes as $key) {
                if (property_exists($this, $key)) {
                    $excludedData[$key] = $this->{$key};
                }
            }
        }

        return array_filter($excludedData);
    }


    // Method to dynamically identify morph fields
    public function getMorphFields($data)
    {
        if ($this->morphMap == []) {
            throw new \Exception("Morph map is not defined in " . get_class($this));
        }
        return []; // Default empty array
    }

    public function whereHasRelations(): array
    {
        return []; // Default empty array
    }

    public function uniqueAttributes(): array
    {
        return []; // Default empty array
    }

    public function handleSpecialCases() {}
    public  function handleImageFolder(): string {
        return $this->imageFolder ?? 'default';
    }
}

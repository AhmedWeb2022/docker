<?php

namespace App\Modules\Base\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImageBase64OrFileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $imageMimes = 'jpeg,png,jpg,gif,svg,webp';
        $videoMimes = 'mp4,avi,mov,wmv,flv,webm,mkv';
        $maxSize = 5120; // KB

        if (is_file($value)) {
            $validator = Validator::make(
                ['file' => $value],
                ['file' => 'mimes:' . $imageMimes . ',' . $videoMimes . "|max:$maxSize"]
            );


            if ($validator->fails()) {
                $fail($validator->errors()->first());
            }
        } else {
            $imagePattern = '/^data:image\\/(jpeg|png|jpg|gif|svg|webp);base64,/';
            $videoPattern = '/^data:video\\/(mp4|avi|mov|wmv|flv|webm|mkv);base64,/';

            if (!preg_match($imagePattern, $value) && !preg_match($videoPattern, $value)) {
                $fail('The ' . $attribute . ' must be a valid base64 image or video string.');
                return;
            }

            $base64Str = preg_replace('/^data:[a-zA-Z0-9\\/\\+]+;base64,/', '', $value);
            $base64_size = strlen(base64_decode($base64Str)) / 1024;
            if ($base64_size > $maxSize) {
                $fail('The ' . $attribute . " must not be greater than $maxSize kilobytes.");
            }
        }
    }
}

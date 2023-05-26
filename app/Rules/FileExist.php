<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;

class FileExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // s3,minio可能無法檢查檔案是否存在
        // Unable to check existence
        if (! Storage::disk('minio-temporary')->fileExists($value)) {
            // 錯誤訊息
            $fail('File does not exist.');
        }
    }
}

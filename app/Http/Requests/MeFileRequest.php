<?php

namespace App\Http\Requests;

use App\Rules\FileExist;
use Illuminate\Foundation\Http\FormRequest;

class MeFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // user -> can (update, model)
        // 用戶能不能update這個物件
        return $this->user()->can('update', $this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // s3,minio可能無法檢查檔案是否存在
            // Unable to check existence
            'file' => ['required', new FileExist],
        ];
    }
}

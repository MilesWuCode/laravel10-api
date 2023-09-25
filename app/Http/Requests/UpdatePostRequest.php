<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // case1
        // return $this->user()->id === $this->route('post.update')->user_id;

        // case2
        // return $this->user()->can('update', $this->route('post.update'));

        // case3.0
        // return $this->user()?->can('update', $this->route('post.update')) ?? false;

        // case3.1 等同 case3.0
        // if ($this->user()) {
        //     return $this->user()->can('update', $this->route('post.update'));
        // }

        // return false;

        // case4.0 使用 Gate
        // use Illuminate\Contracts\Auth\Access\Gate;
        // return app(Gate::class)->authorize('update', $this->route('post.update'));

        // case4.1 使用 Gate 等同 case4.0
        // use Illuminate\Contracts\Auth\Access\Gate;
        // public function authorize(Gate $gate) {}
        // return $gate->authorize('update', $this->route('post.update'));

        return $this->user()?->can('update', $this->post) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:200',
            'content' => 'sometimes|nullable|max:2000',
            'cover' => [
                'sometimes',
                File::image()
                    ->max('10mb')
                    ->dimensions(Rule::dimensions()->maxWidth(1000)->maxHeight(1000)->ratio(1)),
            ],
        ];
    }
}

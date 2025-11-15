<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallpapers;

use Illuminate\Foundation\Http\FormRequest;

class IndexWallpaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => 'nullable|int|min:1|max:20',
            'offset' => 'nullable|int|min:0',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'limit' => $this->query('limit'),
            'offset' => $this->query('offset'),
        ]);
    }
}

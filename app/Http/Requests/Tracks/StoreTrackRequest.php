<?php

declare(strict_types=1);

namespace App\Http\Requests\Tracks;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tableName' => 'required|string|in:wallpapers',
            'tableId' => 'required|integer|exists:wallpapers,id',
            'eventName' => 'required|string|in:download',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tableName' => $this->route('tableName'),
            'tableId' => $this->route('tableId'),
            'eventName' => $this->route('eventName'),
        ]);
    }
}

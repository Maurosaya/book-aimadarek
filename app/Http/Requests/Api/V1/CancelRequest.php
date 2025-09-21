<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for booking cancellation validation
 * 
 * Validates parameters for cancelling an existing booking
 * Supports multilingual error messages
 */
class CancelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by Sanctum middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'motivo' => [
                'nullable',
                'string',
                'max:500'
            ],
            'locale' => [
                'nullable',
                'string',
                'in:es,en,nl'
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'motivo' => __('api.attributes.cancellation_reason'),
            'locale' => __('api.attributes.locale'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'motivo.string' => __('api.validation.motivo_string'),
            'motivo.max' => __('api.validation.motivo_max'),
            'locale.in' => __('api.validation.locale_supported'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set locale from request if provided
        if ($this->has('locale')) {
            app()->setLocale($this->input('locale'));
        }
    }
}

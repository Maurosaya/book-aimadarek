<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request for availability endpoint validation
 * 
 * Validates parameters for checking service availability
 * Supports multilingual error messages
 */
class AvailabilityRequest extends FormRequest
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
            'service_id' => [
                'required',
                'uuid',
                'exists:services,id'
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'party_size' => [
                'nullable',
                'integer',
                'min:1',
                'max:50'
            ],
            'location_id' => [
                'nullable',
                'uuid',
                'exists:locations,id'
            ],
            'locale' => [
                'nullable',
                'string',
                Rule::in(['es', 'en', 'nl'])
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'service_id' => __('api.attributes.service_id'),
            'date' => __('api.attributes.date'),
            'party_size' => __('api.attributes.party_size'),
            'location_id' => __('api.attributes.location_id'),
            'locale' => __('api.attributes.locale'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'service_id.required' => __('api.validation.service_id_required'),
            'service_id.uuid' => __('api.validation.service_id_uuid'),
            'service_id.exists' => __('api.validation.service_id_exists'),
            'date.required' => __('api.validation.date_required'),
            'date.date' => __('api.validation.date_format'),
            'date.after_or_equal' => __('api.validation.date_future'),
            'party_size.integer' => __('api.validation.party_size_integer'),
            'party_size.min' => __('api.validation.party_size_min'),
            'party_size.max' => __('api.validation.party_size_max'),
            'location_id.uuid' => __('api.validation.location_id_uuid'),
            'location_id.exists' => __('api.validation.location_id_exists'),
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

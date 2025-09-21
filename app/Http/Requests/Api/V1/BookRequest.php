<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request for booking creation validation
 * 
 * Validates parameters for creating a new booking
 * Supports multilingual error messages and customer data validation
 */
class BookRequest extends FormRequest
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
            'start' => [
                'required',
                'date_format:Y-m-d\TH:i:sP', // ISO 8601 format with timezone
                'after:now'
            ],
            'party_size' => [
                'nullable',
                'integer',
                'min:1',
                'max:50'
            ],
            'customer' => [
                'required',
                'array'
            ],
            'customer.name' => [
                'required',
                'string',
                'max:255'
            ],
            'customer.email' => [
                'nullable',
                'email',
                'max:255'
            ],
            'customer.phone' => [
                'nullable',
                'string',
                'max:20'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'source' => [
                'nullable',
                'string',
                'max:100'
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
            'start' => __('api.attributes.start_time'),
            'party_size' => __('api.attributes.party_size'),
            'customer' => __('api.attributes.customer'),
            'customer.name' => __('api.attributes.customer_name'),
            'customer.email' => __('api.attributes.customer_email'),
            'customer.phone' => __('api.attributes.customer_phone'),
            'notes' => __('api.attributes.notes'),
            'source' => __('api.attributes.source'),
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
            'start.required' => __('api.validation.start_required'),
            'start.date_format' => __('api.validation.start_format'),
            'start.after' => __('api.validation.start_future'),
            'party_size.integer' => __('api.validation.party_size_integer'),
            'party_size.min' => __('api.validation.party_size_min'),
            'party_size.max' => __('api.validation.party_size_max'),
            'customer.required' => __('api.validation.customer_required'),
            'customer.array' => __('api.validation.customer_array'),
            'customer.name.required' => __('api.validation.customer_name_required'),
            'customer.name.string' => __('api.validation.customer_name_string'),
            'customer.name.max' => __('api.validation.customer_name_max'),
            'customer.email.email' => __('api.validation.customer_email_format'),
            'customer.email.max' => __('api.validation.customer_email_max'),
            'customer.phone.string' => __('api.validation.customer_phone_string'),
            'customer.phone.max' => __('api.validation.customer_phone_max'),
            'notes.string' => __('api.validation.notes_string'),
            'notes.max' => __('api.validation.notes_max'),
            'source.string' => __('api.validation.source_string'),
            'source.max' => __('api.validation.source_max'),
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

    /**
     * Get the validated customer data.
     */
    public function getCustomerData(): array
    {
        return $this->validated()['customer'];
    }
}

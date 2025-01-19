<?php

namespace App\Http\Requests\Campaign;

use App\Enums\ActivityStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'landing_page_url' => [
                'required',
                'url',
                'max:255',
            ],
            'activity_status' => [
                Rule::enum(ActivityStatus::class),
            ],
            'payouts' => [
                'required',
                'array',
                'min:1',
            ],
            'payouts.*.country_id' => [
                'required',
                'uuid',
                Rule::exists('countries', 'id'),
            ],
            'payouts.*.amount_per_interaction' => [
                'required',
                'decimal:0,2',
            ],
        ];
    }
}

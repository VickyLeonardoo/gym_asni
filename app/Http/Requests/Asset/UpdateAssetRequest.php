<?php

namespace App\Http\Requests\Asset;

use App\Enums\AssetCondition;
use App\Enums\AssetStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('asset'));
    }

    public function rules(): array
    {
        return [
            'asset_code' => ['required', 'string', 'max:50', Rule::unique('assets', 'asset_code')->ignore($this->route('asset'))],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:150', Rule::unique('assets', 'serial_number')->ignore($this->route('asset'))],
            'purchase_store_name' => ['nullable', 'string', 'max:255'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'service_contact_name' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'warranty_expires_at' => ['nullable', 'date', 'after_or_equal:purchase_date'],
            'maintenance_interval_months' => ['required', 'integer', 'min:1', 'max:120'],
            'status' => ['required', Rule::enum(AssetStatus::class)],
            'condition' => ['required', Rule::enum(AssetCondition::class)],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

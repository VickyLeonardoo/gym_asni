<?php

namespace App\Http\Requests\Maintenance;

use App\Enums\MaintenanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('maintenance'));
    }

    public function rules(): array
    {
        return [
            'service_contact_id' => ['nullable', 'exists:service_contacts,id'],
            'scheduled_at' => ['required', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:scheduled_at'],
            'status' => ['required', Rule::enum(MaintenanceStatus::class)],
            'cost' => ['exclude_unless:status,'.MaintenanceStatus::Completed->value, 'required_if:status,'.MaintenanceStatus::Completed->value, 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:2000'],
            'resolution' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

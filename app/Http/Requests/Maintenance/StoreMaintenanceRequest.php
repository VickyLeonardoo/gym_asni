<?php

namespace App\Http\Requests\Maintenance;

use App\Models\AssetMaintenance;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', AssetMaintenance::class);
    }

    public function rules(): array
    {
        return [
            'service_contact_id' => ['nullable', 'exists:service_contacts,id'],
            'scheduled_at' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }
}

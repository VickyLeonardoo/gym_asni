<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class CompleteMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('maintenance'));
    }

    public function rules(): array
    {
        return [
            'completed_at' => ['required', 'date', 'after_or_equal:'.$this->route('maintenance')->scheduled_at->format('Y-m-d')],
            'cost' => ['required', 'numeric', 'min:0'],
            'resolution' => ['required', 'string', 'max:2000'],
        ];
    }
}

<?php

namespace App\Http\Requests\ServiceContact;

use App\Models\ServiceContact;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', ServiceContact::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('service_contacts', 'name')],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

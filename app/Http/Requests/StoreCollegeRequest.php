<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollegeRequest extends FormRequest
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
            'branch_id' => ['required', 'exists:branches,id'],
            'name_en' => ['required', 'string', 'max:50'],
            'name_ar' => ['required', 'string', 'max:50'],
            'code' => ['required', 'string', 'max:10'],
            'created_by' => ['exists:users,id'],
            'updated_by' => ['exists:users,id'],
        ];
    }
}

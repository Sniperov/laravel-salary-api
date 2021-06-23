<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'salary' => 'required|integer',
            'norma' => 'nullable|integer',
            'count_worked_days' => 'required|integer',
            'tax_ms' => 'required|boolean',
            'year' => 'required|integer',
            'mouth' => 'required|integer',
            'is_pensioner' => 'required|boolean',
            'is_invalid' => 'required|boolean',
            'invalid_group' => 'nullable|integer|min:1|max:3'
        ];
    }
}

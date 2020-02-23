<?php

namespace App\Http\Requests\Workcash;

use Illuminate\Foundation\Http\FormRequest;

class CompaniesRequest extends FormRequest
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
            'name' => 'required',
            'description' => '',
            'work_start_date' => 'date',
            'work_end_date' => 'date|nullable',
            'position' => 'required',
            'yearly_income' => 'numeric',
            'satisfaction_rate' => 'integer'
        ];
    }
}

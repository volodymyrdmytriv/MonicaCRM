<?php

namespace App\Http\Requests\Workcash;

use Illuminate\Foundation\Http\FormRequest;

class JobsRequest extends FormRequest
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
            'title' => 'required|max:100',
            'description' => 'required|max:500',
            'start_date' => 'date',
            'coworkers' => 'array',
            'coworkers.*' => 'string',
        ];
    }
}

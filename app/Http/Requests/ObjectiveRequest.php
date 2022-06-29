<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectiveRequest extends FormRequest
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
            'description' => 'required|min:5',
            'weighing' => 'required',
            'year' => 'required',
            'quarter' => 'required',
            'evidence' => '',
            'approved' => '',
            'observation' => '',
            'real_weighing' => ''
        ];
    }
}

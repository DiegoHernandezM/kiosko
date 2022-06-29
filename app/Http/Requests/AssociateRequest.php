<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssociateRequest extends FormRequest
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
            'lastnames' => 'required|min:5',
            'entry_date' => 'required|date',
            'employee_number' => 'required|unique:associates,employee_number',
            'area_id' =>'required',
            'subarea_id' =>'required',
            'vacations_available' => 'required'
        ];
    }
}

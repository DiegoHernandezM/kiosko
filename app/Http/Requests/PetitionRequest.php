<?php
/**
 * Created by PhpStorm.
 * User: aicarrillo
 * Date: 11/03/22
 * Time: 16:08
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class PetitionRequest extends FormRequest
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
            'date' => 'required',
            'petition_description' => 'required',
            'comment' => 'required',
            'approved' => 'required',
            'period' => 'nullable',
            'comment_by_admin' => 'nullable',
            'files' => 'nullable'
        ];
    }
}

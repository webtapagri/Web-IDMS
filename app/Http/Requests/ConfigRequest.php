<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigRequest extends FormRequest
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
			'werks' => 'required',
			'month' => 'required',
			'year' => 'required',
        ];
    }
	
	public function messages()
	{
		
		return [
			'required'  => 'Harap bagian :attribute di isi.',
			'unique'    => ':attribute sudah digunakan',
		];
	}
}

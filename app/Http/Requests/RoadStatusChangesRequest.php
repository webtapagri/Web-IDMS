<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Road;

class RoadStatusChangesRequest extends FormRequest
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
			'status_id' => 'required',
			'category_id' => 'required',
			'segment' => 'required|numeric|max:9|min:1|'.Rule::unique(Road, 'block_code'),
			
           
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

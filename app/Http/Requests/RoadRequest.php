<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoadRequest extends FormRequest
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
			'company_code' => 'required',
			'werks' => 'required',
			'afdeling_code' => 'required',
			'block_code' => 'required',
			'status_id' => 'required',
			'category_id' => 'required',
			// 'asset_code' => 'required',
			'segment' => 'required|numeric|max:9',
			'total_length' => 'required|numeric',
			
            // 'road_code' => [
				// 'required', 
				// Rule::unique('TM_ROAD')->where(function ($query) {
					// return $query->whereRaw('deleted_at is null');
				// })
			// ],
			
            // 'road_name' => [
				// 'required', 
				// Rule::unique('TM_ROAD')->where(function ($query) {
					// return $query->whereRaw('deleted_at is null');
				// })
			// ],
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
